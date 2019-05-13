<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/4/24
 * Time: 14:27
 */

namespace App\Models;
use App\Models\Traits\HasConstant;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StuAudit
 *
 * @property int $sid 学生申请的id
 * @property int $state 0:未审核 1:已阅 2:已处理
 * @property string|null $inter_area 面试地点
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuAudit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuAudit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuAudit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuAudit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuAudit whereInterArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuAudit whereSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuAudit whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuAudit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StuAudit extends Model
{
    use HasConstant;
    protected $table = 'stu_audit';
    public $timestamps = false;

    public $primaryKey = 'sid';

    protected $fillable = ['sid','state', 'inter_area', 'inter_time','updated_at'];

    // 0为审核成功，1为审核失败，2为审核中，3已撤回
    public static function constants($type = 'ass_type'): array
    {
        switch ($type) {
            case 'state':
                return [
                    0       => '未审阅',
                    1       => '已阅',
                    2       => '已处理',
                    3       => '落选',
                ];

            default:
                return [];
        }
    }
}