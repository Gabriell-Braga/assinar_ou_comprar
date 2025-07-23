<?php

namespace App;

class Utils
{
    static function validate($data=[], $rules=[], $messages=[]) {
        $validator = \Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'error' => $validator->errors(),
            ]));
        }
        return false;
    }



    static function mail($to, $subject, $body) {
        $to = is_array($to)? $to: [$to];
        $body = nl2br($body);

        $smtp = config('mail.mailers.smtp');
        $smtp['host'] = \App\Models\Setting::getValue('mail.mailers.smtp.host');
        $smtp['port'] = \App\Models\Setting::getValue('mail.mailers.smtp.port');
        $smtp['username'] = \App\Models\Setting::getValue('mail.mailers.smtp.username');
        $smtp['password'] = \App\Models\Setting::getValue('mail.mailers.smtp.password');
        config()->set('mail.mailers.smtp', $smtp);

        \Mail::send('emails.mail', ['body' => $body], function($mail) use($to, $subject, $body) {
            $from = config('mail.from');
            $sent = $mail->from($from['address'], $from['name'])
            // $sent = $mail->from('MAIL_FROM_NAME', $data['from']['name'])
                ->subject($subject)->to($to);
        });
            
        if (empty(\Mail::failures())) {
            return \DB::table('email_sents')->insert([
                'to' => json_encode($to),
                'subject' => $subject,
                'body' => $body,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return false;
    }



    static function path($path) {
        return implode(DIRECTORY_SEPARATOR, preg_split('/\\\|\//', $path));
    }



    static function realpath($path) {
        return realpath(self::path($path));
    }



    static function fieldCreate($className, $field_name, $callback) {
        $table_name = (new $className)->getTable();

        if (! \Schema::hasTable($table_name)) {
            \Schema::create($table_name, function($table) {
                $table->increments('id');
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            });
        }

        if (\Schema::hasColumn($table_name, $field_name)) return;

        \Schema::table($table_name, function($table) use($callback) {
            call_user_func($callback, $table);
        });
    }
    
    

    static function fieldUpdate($className, $field_name, $callback) {
        $table_name = (new $className)->getTable();

        if (! \Schema::hasTable($table_name)) {
            \Schema::create($table_name, function($table) {
                $table->increments('id');
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            });
        }

        if (! \Schema::hasColumn($table_name, $field_name)) return;

        \Schema::table($table_name, function($table) use($callback) {
            call_user_func($callback, $table);
        });
    }


    static function dbSeed($class, $id, $callback) {
        if (! $model = (new $class)->find($id)) {
            call_user_func($callback);
        }
    }
}