<?php


namespace App;
use Illuminate\Database\Eloquent\Model;
/**
 * App\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $parent_permission
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereParentPermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereSlug($value)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description','parent_permission'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }

    /**
     * @param $roleIDs
     * @return array
     */
    public function syncRoles($roleIDs)
    {
        return $this->roles()->sync($roleIDs);
    }

    /**
     * @param $roleIDs
     * @return array
     */
    public function syncWithoutDetachingRoles($roleIDs)
    {
        return $this->roles()->syncWithoutDetaching($roleIDs);
    }

}
