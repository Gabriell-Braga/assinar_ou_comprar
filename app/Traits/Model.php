<?php

namespace App\Traits;

trait Model
{
    public static function bootModel() {
        $_function = function($model, $event) {
            $firebase_databaseurl = env('FIREBASE_DATABASEURL');
            $settings = json_decode(base64_decode(env('FIREBASE_CREDENTIALS_BASE64_JSON')), true);
            if (! is_array($settings)) return;

            $service_account = \Cache::remember('firebase-service-account', (60*24), function() use($settings) {
                return \Kreait\Firebase\ServiceAccount::fromValue($settings);
            });

            $factory = (new \Kreait\Firebase\Factory())->withServiceAccount($service_account)->withDatabaseUri($firebase_databaseurl);

            $table = $model->getTable();
            $factory->createDatabase()->getReference('push')->set([
                'app_name' => env('APP_NAME'),
                'url' => url('/'),
                'date' => date('Y-m-d H:i:s'),
                'session' => "{$table}:{$event}",
                'table' => $table,
                'model' => $model,
            ]);
        };

        self::created(function($model) use($_function) {
            $_function($model, 'created');
        });

        self::updated(function($model) use($_function) {
            $_function($model, 'updated');
        });

        self::deleted(function($model) use($_function) {
            $_function($model, 'deleted');
        });
    }

    // Escopo de busca
    // Model::search(['search'=>'Term']);
    public function scopeSearch($query, $params=[]) {
        $params = (array) array_merge([
            'q' => '',
            'page' => 1,
            'perpage' => 10,
            'orderby' => 'id',
            'order' => 'desc',
        ], $params);

        $attrs = $this->fillable;

        foreach($attrs as $key) {
            if (! isset($params[ $key ])) continue;
            $value = $params[ $key ];
            $op = isset($params["{$key}_op"])? $params["{$key}_op"]: 'eq';
            
            // ?age=18&age_op=gt
            if ($op=='gt') {
                $query = $query->where($key, '>', intval($value));
                continue;
            }
            
            // ?age=18&age_op=gte
            else if ($op=='gte') {
                $query = $query->where($key, '>=', intval($value));
                continue;
            }
            
            // ?age=18&age_op=lt
            else if ($op=='lt') {
                $query = $query->where($key, '<', intval($value));
                continue;
            }

            // ?age=18&age_op=lte
            else if ($op=='lte') {
                $query = $query->where($key, '<=', intval($value));
                continue;
            }

            // ?name=john+doe8&name_op=like
            else if ($op=='like') {
                $query = $query->where($key, 'like', "%{$value}%");
                continue;
            }

            // ?field_id=1,22,33&name_op=in
            // ?field_id[]=1&field_id[]=22&field_id[]=33&name_op=in
            else if ($op=='in') {
                $value = is_array($value)? $value: preg_split('/[^a-zA-Z0-9]/', $value);
                $query = $query->whereIn($key, $value);
                continue;
            }

            // ?rate=50,100&rate_op=between
            else if ($op=='between') {
                $value = is_array($value)? $value: preg_split('/[^a-zA-Z0-9]/', $value);
                $query = $query->whereBetween($key, $value);
            }

            // any other works as equal or in
            else {
                if (is_array($value)) {
                    $query = $query->whereIn($key, $value);
                    continue;
                }

                $query = $query->where($key, $value);
                continue;
            }
        }

        // ?search=term1+term2
        if ($params['q']) {
            $terms = preg_split('/[^a-zA-Z0-9]/', $params['q']);
            foreach($terms as $term) {
                $query = $query->where(function($query) use($attrs, $term) {
                    foreach($attrs as $key) {
                        $query->orWhere($key, 'like', "%{$term}%");
                    }
                });
            }
        }

        $query = $query->orderBy($params['orderby'], $params['order']);
        return $query->paginate($params['perpage']);
    }


    // Retorna SQL final
    // $this->where('active', 1)->toRawSql();
    public function scopeToRawSql($query) {
        return call_user_func(function($query) {
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            $needle = '?';
            foreach ($bindings as $replace){
                $pos = strpos($sql, $needle);
                if ($pos !== false) {
                    if (gettype($replace) === "string") {
                        $replace = ' "'.addslashes($replace).'" ';
                    }
                    $sql = substr_replace($sql, $replace, $pos, strlen($needle));
                }
            }
            return $sql;
        }, $query);
    }
    
    
    public function validate($data=[]) {
        return \Validator::make($data, []);
    }

    public function store($data=[]) {
        $data = array_merge($this->toArray(), $data);

        $table_name = $this->getTable();
        $table_pk = $this->getKeyName();
        $id = isset($data[$table_pk])? $data[$table_pk]: false;

        if ($validator = $this->validate($data) AND $validator->fails()) {
            throw new \Exception(json_encode([
                'message' => 'Há erros de validação',
                'fields' => $validator->errors(),
            ]));
        }

        if ($id AND $save=self::find($id)) {
            $save->fill($data)->save();
        }
        else {
            $save = self::create($data);
        }

        return $save;
    }


    public function remove($forced=false) {
        if (! $this->id) return false;
        
        if ($forced==false AND \Schema::hasColumn($this->getTable(), 'deleted_at')) {
            $this->deleted_at = date('Y-m-d H:i:s');
            $this->store();
            return $this;
        }

        $this->delete();
        return $this;
    }



    // public function search($params=null) {

    //     $params = $params===null? request()->all(): $params;
    //     $params = (object) array_merge([
    //         'perpage' => 15,
    //         'page' => 1,
    //         'search' => '',
    //         'orderby' => 'id',
    //         'order' => 'desc',
    //     ], $params);

    //     $query = $this->orderBy($params->orderby, $params->order);

    //     foreach($params as $key=>$value) {
    //         if (! in_array($key, $this->fillable)) continue;
    //         $op = isset($params->{"{$key}_op"})? $params->{"{$key}_op"}: 'eq';
    //         if ($op=='eq') { $op = '='; }
    //         else if ($op=='gt') { $op = '>'; }
    //         else if ($op=='gte') { $op = '>='; }
    //         else if ($op=='lt') { $op = '<'; }
    //         else if ($op=='lte') { $op = '<='; }
    //         else if ($op=='like') { $op = 'like'; }
    //         else { $op = '='; }

    //         $query = $query->where($key, $op, $value);
    //     }

    //     return $query->paginate($params->perpage);

    //     // $attrs = $this->attributes;
        
    //     // $query = new static;

    //     // if ($params->search) {
    //     //     $query = $query->where(function($query) use($attrs) {
    //     //         foreach($attrs as $key=>$val) {
    //     //             if (is_array($val)) continue;
    //     //             $query->orWhere($key, 'like', "%{$val}%");
    //     //         }
    //     //     });
    //     // }

    //     // return $query;
    // }

    public function apiSearch($params=[]) {
        $default_params = [
            'search' => '',
            'page' => '',
            'perpage' => 10,
            'orderby' => 'updated_at',
            'order' => 'desc',
        ];

        $params = array_merge($default_params, $params);

        // Define query
        $query = $this;

        // Where param equal field
        foreach(\Schema::getColumnListing($this->getTable()) as $field) {
            if (isset($params[$field])) {
                $query = $query->where($field, $params[$field]);
            }
        }

        // Search
        $search_terms = array_filter(preg_split('/[^a-zA-Z0-9]/', $params['search']), 'strlen');
        if (sizeof($search_terms)>0) {
            foreach($this->attributes as $key=>$val) {
                if ($key=='id') continue;
                foreach($search_terms as $search) {
                    $query = $query->orWhere($key, 'like', "%{$search}%");
                }
            }
        }

        // Equal
        foreach($this->attributes as $key=>$val) {
            if (isset($params[ $key ]) AND !empty($params[ $key ])) {
                $query = $query->where($key, $params[ $key ]);
            }
        }
        
        $query = $query->orderBy($params['orderby'], $params['order']);
        // dd(vsprintf(str_replace(array('?'), array('\'%s\''), $query->toSql()), $query->getBindings()));
        return $query->paginate($params['perpage']);
    }

    static function pushNotification($key, $data=[]) {
        $credentials = json_decode(base64_decode(env('FIREBASE_CREDENTIALS_BASE64_JSON')), true);
        if (! is_array($credentials)) return;
        $database = (new \Kreait\Firebase\Factory)->withServiceAccount($credentials)->createDatabase();
        return $database->getReference($key)->set($data);
    }
}