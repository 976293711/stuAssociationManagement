<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/4/24
 * Time: 16:08
 */

namespace App\Models;


use App\Models\Traits\HasConstant;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StuRegistrationManages
 *
 * @property int $sid 学生id
 * @property int|null $uid 用户的uid
 * @property int $ass_id 申请的社团id
 * @property string $name 学生名字
 * @property string $number 学生学号
 * @property int|null $sex 学生性别 1:男生 2:女生
 * @property string|null $phone 学生电话
 * @property string|null $college 学生学院
 * @property string|null $grade 学生年级
 * @property string|null $detail 学生自我介绍
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereAssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereCollege($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StuRegistrationManages whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StuRegistrationManages extends Model
{
    use HasConstant;
    protected $table = 'stu_registration_manages';
    public $timestamps = false;

    public $primaryKey = 'sid';

    public function associationInfo()
    {
        return $this->belongsTo(AssociationManagement::class, 'ass_id');
    }

    public function stuAuditInfo(){
        return $this->hasOne(StuAudit::class, 'sid');
    }

    // 0为审核成功，1为审核失败，2为审核中，3已撤回
    public static function constants($type = 'ass_type'): array
    {
        switch ($type) {
            case 'sex':
                return [
                    1       => '男',
                    2       => '女',
                ];

            default:
                return [];
        }
    }
}