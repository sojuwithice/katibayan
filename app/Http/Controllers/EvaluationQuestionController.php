<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use Illuminate\Support\Facades\DB;

class EvaluationQuestionController extends Controller
{
    // REMOVED: locked() method since we're not locking the system anymore

    public function index()
    {
        $questions = EvaluationQuestion::orderBy('order')->get();
        
        // Manually add evaluations_count to each question
        $questions->each(function($question) {
            $question->evaluations_count = $question->getEvaluationsCountAttribute();
        });

        return view('sk.evaluation.questions', [
            'questions' => $questions,
            // REMOVED: 'locked' parameter since we're not using it
        ]);
    }

    public function list()
    {
        $questions = EvaluationQuestion::orderBy('order')->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'order' => $question->order,
                    'is_default' => $question->is_default,
                    'evaluations_count' => $question->getEvaluationsCountAttribute(),
                    'is_active' => $question->is_active
                ];
            });

        return response()->json($questions);
    }

    public function store(Request $request)
    {
        // REMOVED: lock check - always allow creating new questions
        $question = EvaluationQuestion::create([
            'question_text' => $request->question_text,
            'order' => EvaluationQuestion::max('order') + 1,
            'is_default' => false,
            'is_active' => true
        ]);

        return response()->json(['success' => true, 'question' => $question]);
    }

    public function update(Request $request, $id)
    {
        $question = EvaluationQuestion::findOrFail($id);
        
        // Check if question has evaluations
        $hasEvaluations = $question->hasEvaluations();
        
        // Only allow update if no evaluations exist for non-default questions
        if (!$question->is_default && $hasEvaluations) {
            return response()->json(['error' => 'Cannot edit question with existing responses'], 403);
        }

        $question->update([
            'question_text' => $request->question_text,
            'is_active' => $request->is_active ?? $question->is_active
        ]);

        return response()->json(['success' => true]);
    }

    public function saveAll(Request $request)
    {
        // REMOVED: System-wide lock check
        // We'll handle locking per question instead

        $questions = $request->input('questions', []);
        
        \Log::info('Questions to save:', ['count' => count($questions)]);
        
        // Validate input
        if (empty($questions)) {
            return response()->json([
                'success' => false,
                'message' => 'No questions provided.',
                'error' => 'no_questions'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $stats = [
                'updated' => 0,
                'order_changed' => 0,
                'skipped' => 0,
                'added_new' => 0
            ];
            
            foreach ($questions as $index => $questionData) {
                $questionText = trim($questionData['question_text'] ?? '');
                
                // Skip if question text is empty
                if (empty($questionText)) {
                    \Log::warning('Empty question text at index: ' . $index);
                    continue;
                }
                
                if (str_starts_with($questionData['id'], 'new-')) {
                    // Create new question
                    EvaluationQuestion::create([
                        'question_text' => $questionText,
                        'order' => $index + 1,
                        'is_default' => false,
                        'is_active' => true
                    ]);
                    $stats['added_new']++;
                    \Log::info('Created new question:', ['text' => $questionText, 'order' => $index + 1]);
                } else {
                    // Update existing question
                    $questionId = $questionData['id'];
                    $question = EvaluationQuestion::find($questionId);
                    
                    if ($question) {
                        // Always update order (even for locked questions)
                        $oldOrder = $question->order;
                        $question->update(['order' => $index + 1]);
                        
                        if ($oldOrder != $index + 1) {
                            $stats['order_changed']++;
                        }
                        
                        // Check if can update text
                        $canUpdateText = $question->is_default || !$question->hasEvaluations();
                        
                        if ($canUpdateText) {
                            $question->update([
                                'question_text' => $questionText,
                                'is_active' => true
                            ]);
                            $stats['updated']++;
                            \Log::info('Updated question text:', ['id' => $questionId, 'text' => $questionText]);
                        } else {
                            // Question has evaluations and is not default
                            // Don't update text, just log it
                            $stats['skipped']++;
                            \Log::info('Skipped text update for locked question:', [
                                'id' => $questionId, 
                                'original_text' => $question->question_text
                            ]);
                        }
                    } else {
                        \Log::warning('Question not found:', ['id' => $questionId]);
                    }
                }
            }

            DB::commit();
            
            // Get updated list
            $updatedQuestions = EvaluationQuestion::orderBy('order')
                ->get()
                ->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'order' => $question->order,
                        'is_default' => $question->is_default,
                        'evaluations_count' => $question->getEvaluationsCountAttribute()
                    ];
                });

            \Log::info('Save successful', $stats);
            
            return response()->json([
                'success' => true,
                'message' => 'Questions saved successfully!',
                'questions' => $updatedQuestions,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving questions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving questions: ' . $e->getMessage(),
                'error' => 'server_error'
            ], 500);
        }
    }

    public function destroy($id)
    {
        // REMOVED: System-wide lock check

        $question = EvaluationQuestion::findOrFail($id);
        
        // Prevent deletion of default questions or questions with responses
        if ($question->is_default) {
            return response()->json(['error' => 'Cannot delete default questions'], 403);
        }

        if ($question->hasEvaluations()) {
            return response()->json(['error' => 'Cannot delete question with existing responses'], 403);
        }

        $question->delete();
        
        // Reorder remaining questions
        $this->reorderQuestions();

        return response()->json(['success' => true]);
    }

    // Helper method to reorder questions
    private function reorderQuestions()
    {
        $questions = EvaluationQuestion::orderBy('order')->get();
        
        foreach ($questions as $index => $question) {
            $question->update(['order' => $index + 1]);
        }
    }

    // EvaluationController.php
public function restoreDefaultQuestions(Request $request)
{
    try {
        DB::beginTransaction();
        
        // 1. Delete all current questions
        EvaluationQuestion::query()->delete();
        
        // 2. Use the same default questions from seeder
        $defaultQuestions = [
            ['question_text' => 'Was the purpose of the program/event explained clearly?', 'order' => 1, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Was the time given for the program/event enough?', 'order' => 2, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Were you able to join and participate in the activities?', 'order' => 3, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did you learn something new from this program/event?', 'order' => 4, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did the SK officials/facilitators treat all participants fairly and equally?', 'order' => 5, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did the SK officials/facilitators show enthusiasm and commitment in leading the program/event?', 'order' => 6, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Overall, are you satisfied with this program/event?', 'order' => 7, 'is_default' => true, 'is_active' => true],
        ];
        
        // 3. Insert default questions
        $questions = [];
        foreach ($defaultQuestions as $questionData) {
            $questions[] = array_merge($questionData, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        EvaluationQuestion::insert($questions);
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Default questions restored successfully',
            'count' => count($defaultQuestions)
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Failed to restore default questions: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => 'Failed to restore default questions: ' . $e->getMessage()
        ], 500);
    }
}
}