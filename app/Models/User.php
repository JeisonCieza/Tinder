<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\BasicGroupEnum;
use App\Enums\RelationshipGoalsEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'relationship_goals' => RelationshipGoalsEnum::class
        ];
    }

    //Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $basics = Basic::all();

            //if wants children in the future
            $basic = $basics->where('group', BasicGroupEnum::children)->first();
            $user->basics()->attach($basic);

            //zodiac
            $basic = $basics->where('group', BasicGroupEnum::zodiac)->first();
            $user->basics()->attach($basic);
        });

    }

    function basics() : BelongsToMany
    {
        return $this->belongsToMany(Basic::class, 'basic_user');
    }

    function languages() : BelongsToMany {
        return $this->belongsToMany(Language::class,'language_user');
    }

    /* Swipe model relationships */

    /* user has many swipes */
    function swipes() : HasMany {
        return $this->hasMany(Swipe::class,'user_id');
    }

    /* allows to check if user has swiped with another user */
    function hasSwiped (User $user, $type=null) : bool {

        $query= $this->swipes()->where('swiped_user_id',$user->id);

        if ($type !== null) {
            $query->where('type',$type);
        }

        return $query->exists();
    }

    // exclude users who has already been siped by the auth user
    function scopeWhereNotSwiped($query) { 
        //Exclude the users whose IDs are in the result of the subquery
        return $query->whereNotIn('id',function($subquery){
            // select the swiped_user_id from the swipes table where the 
            // user_id is the auth id
            $subquery->select('swiped_user_id')
                ->from('swipes')
                ->where('user_id',auth()->id());
        });
    }

    /* MATCH */
    public function matches()
    {
        return $this->hasManyThrough(
            SwipeMatch::class,Swipe::class,'user_id','swipe_id_1','id','id'
            
        )->orWhereHas('swipe2',function($query){
            $query->where('user_id',$this->id);
        });
    }

    /* user can have many conversations */
    public function conversations()
    {
        return $this->hasMany(Conversation::class,'sender_id')->orWhere('receiver_id',$this->id);
    }

}
