<p>Шановний {{ $appointment->doctor->name }},</p>

<p>У вашому розкладі відбулися зміни:</p>

<p>Пацієнт: {{ $appointment->patient->firstname }} {{ $appointment->patient->lastname }}</p>
<p>Час: {{ $appointment->start_time }} - {{ $appointment->end_time }}</p>

<p>З повагою, <br> Ваша клініка</p>