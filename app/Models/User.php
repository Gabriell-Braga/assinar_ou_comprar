<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, \App\Traits\Model;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'id',
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'whatsapp',
		'meta',
		'level',
		'created_at',
		'updated_at'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function boot() {
        parent::boot();

        self::created(function($user){
            (new \App\Mail\Register($user))->send();
        });
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = $value? \Hash::make($value): null;
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = ucwords(mb_strtolower($value));
    }

    public function getBackgroundAttribute($value) {
        return $value? $value: 'https://source.unsplash.com/random/800x300/';
    }

    public function getPhotoAttribute($value) {
        return $value? $value: 'https://www.flaticon.com/svg/static/icons/svg/847/847969.svg';
    }

    public function getMetaAttribute($value) {
        $value = json_decode($value, true);
        $value = is_array($value)? $value: [];
        return array_merge([
            'photo' => 'https://www.flaticon.com/svg/static/icons/svg/847/847969.svg',
            'background' => '',
            'description' => '',
        ], $value);
    }

    public function setMetaAttribute($value) {
        if (is_array($value)) { $value = json_encode($value); }
        $this->attributes['meta'] = $value;
    }


    // https://laravel.com/docs/8.x/validation#available-validation-rules
    public function validate($data=[]) {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email:rfc,dns'],
            'password' => [],
        ];

        // Update
        if (isset($data['id']) AND !empty($data['id'])) {
            $rules['email'][] = "unique:users,email,{$data['id']}";
        }
        
        // Insert
        else {
            $rules['email'][] = 'unique:users,email';
            $rules['password'][] = 'required';
            $rules['password'][] = 'confirmed';
        }

        return \Validator::make($data, $rules);
    }


    static function passwordToken($data=[]) {
        $data = array_merge(['email'=>''], $data);

        $rules = [
            'email' => 'required|email:rfc,dns',
        ];

        \App\Utils::validate($data, $rules);

        if ($user = static::where('email', $data['email'])->first()) {
            $set = \Illuminate\Support\Facades\Password::sendResetLink(['email'=>$data['email']], function($mail) use($data) {
                // $mail->bcc($data['email']);
                // $mail->subject('Alteração de senha');
            });
    
            if ($reset = \DB::table('password_resets')->where('email', '=', $data['email'])->first()) {
                (new \App\Mail\Register($user, $reset->token))->send();
            }
    
            return $set;
        }

        return false;
    }

    static function passwordReset($data=[]) {
        $data = array_merge([
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'token' => '',
        ], $data);

        $rules = [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|required_with:password_confirmation|same:password_confirmation',
            'token' => 'required',
        ];

        \App\Utils::validate($data, $rules);

        if ($reset = \DB::table('password_resets')->where('email', '=', $data['email'])->where('token', '=', $data['token'])->first()) {
            $user = static::where('email', '=', $data['email'])->first();
            $user->password = $data['password'];
            return $user->save();
        }
        
        throw new \Exception('Token ou email inválido');
    }

    public function verificationLink() {
        return url("/user-verification/{$this->id}");
    }

    public function notify($data=[]) {
        $data['user_id'] = $this->id;
        return (new \App\Models\UserNotification)->store($data);
    }

    public function notifications($data=[]) {
        $data = (object) array_merge([
            'seen' => null,
        ], $data);

        $query = new \App\Models\UserNotification;

        if ($data->seen!==null) {
            $query = $query->where('seen', $data->seen);
        }

        return $query;
    }
}