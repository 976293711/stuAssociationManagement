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
 * App\Models\AssNotice
 *
 * @property int $ass_id 社团id
 * @property string $ass_notice 社团公告
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssNotice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssNotice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssNotice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssNotice whereAssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssNotice whereAssNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssNotice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AssNotice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssNotice extends Model
{
    use HasConstant;
    protected $table = 'assocaition_notice';
    public $timestamps = false;
    public $primaryKey = 'ass_id';

    protected $fillable = ['ass_id', 'ass_notice', 'updated_at'];
}