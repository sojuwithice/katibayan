<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EvaluationQuestion;

class EvaluationQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $defaultQuestions = [
            ['question_text' => 'Was the purpose of the program/event explained clearly?', 'order' => 1, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Was the time given for the program/event enough?', 'order' => 2, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Were you able to join and participate in the activities?', 'order' => 3, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did you learn something new from this program/event?', 'order' => 4, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did the SK officials/facilitators treat all participants fairly and equally?', 'order' => 5, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Did the SK officials/facilitators show enthusiasm and commitment in leading the program/event?', 'order' => 6, 'is_default' => true, 'is_active' => true],
            ['question_text' => 'Overall, are you satisfied with this program/event?', 'order' => 7, 'is_default' => true, 'is_active' => true],
        ];

        foreach ($defaultQuestions as $question) {
            EvaluationQuestion::create($question);
        }
    }
}