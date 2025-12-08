<x-mail::message>
# {{ $data['is_lockout'] ? '🚨 ACCOUNT LOCKOUT ALERT' : '⚠️ Failed Login Attempts Alert' }}

**User Details:**<br>
Name: {{ $data['user_name'] }}<br>
Account Number: {{ $data['account_number'] }}<br>
Email: {{ $data['email'] }}

**Security Alert:**<br>
Failed Login Attempts: **{{ $data['attempts'] }}** out of {{ $data['max_attempts'] }}<br>
Remaining Attempts: **{{ $data['remaining_attempts'] }}**<br>
@if($data['is_lockout'])
**STATUS: ACCOUNT TEMPORARILY LOCKED**<br>
Lockout Duration: {{ $data['lockout_duration'] }} minutes<br>
@else
**WARNING:** Only {{ $data['remaining_attempts'] }} attempt(s) remaining before lockout
@endif

**Technical Details:**<br>
IP Address: {{ $data['ip_address'] }}<br>
Timestamp: {{ $data['timestamp'] }}<br>
User Agent: {{ $data['user_agent'] }}

<x-mail::button :url="route('admindashb')">
View in Admin Dashboard
</x-mail::button>

**Recommended Action:**<br>
1. Review this login activity<br>
2. Check if this is legitimate user activity<br>
3. Contact user if necessary<br>
4. Monitor for further suspicious activity

Thanks,<br>
{{ config('app.name') }} Security System
</x-mail::message>