<?php

return [
    // Denda per hari keterlambatan (dalam rupiah).
    // Bisa dioverride via env: LOAN_FINE_PER_DAY
    'fine_per_day' => (int) env('LOAN_FINE_PER_DAY', 1000),
];
