<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/4/23
 * Time: 16:28
 */

namespace App\Models;


use App\Models\Traits\HasConstant;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\AssociationManagement
 *
 * @property int $ass_id 社团id
 * @property string $ass_name 社团名字
 * @property string|null $ass_img 社团头像
 * @property string|null $ass_introduction 社团简介
 * @property int $ass_type 社团类型：1.校级直属类，2.校级学术类 3.校级实践类 4.校级文娱类 5.大学生体育联合会 6.学院类
 * @property int $state 1:已保存 -1:已删除
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement whereAssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement whereAssImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement whereAssIntroduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement whereAssName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement whereAssType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssociationManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssociationManagement extends Model
{
    use HasConstant;

    const SELf_MEDIA = 1;
    protected $table = 'association_manages';

    public $timestamps = false;
    public $primaryKey = 'ass_id';

    protected $fillable = ['ass_id', 'ass_name', 'ass_img', 'ass_introduction', 'ass_type', 'state', 'charge_phone','created_at'];

    // 0为审核成功，1为审核失败，2为审核中，3已撤回
    public static function constants($type = 'ass_type'): array
    {
        switch ($type) {
            case 'ass_type':
                return [
                    self::SELf_MEDIA => '自媒体',
                ];

            default:
                return [];
        }
    }
}