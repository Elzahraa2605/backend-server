<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildApp extends Model
{
    use HasFactory;

    /**
     * الحقول المسموح بحفظها وتحديثها جماعياً من الـ APIs
     */
    protected $fillable = [
        'child_id', 
        'app_name', 
        'package_name', 
        'is_blocked', 
        'time_limit',
        'status',
        'app_icon'
    ];

    /**
     * تحويل صيغ البيانات عند الإرسال والاستقبال لضمان سلامة قراءتها في الموبايل
     */
    protected $casts = [
        'is_blocked' => 'integer', // 🚀 تم تعديله إلى integer (0 أو 1) ليتوافق 100% مع فحص الأندرويد اللحظي وقواعد البيانات الصارمة
        'time_limit' => 'integer',
    ];
}