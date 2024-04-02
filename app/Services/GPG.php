<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
//use gnupg;

class GPG
{

    public function file_encryption_gnupg($nama_fail)
    {

        $file_txt = config('services.ispeks.local_folder_in').$nama_fail;
        $public_key_id = config('services.ispeks.public_key_id'); 
       
        $command = "gpg --batch --no-tty --yes --trust-model always -r ".$public_key_id." --pinentry-mode=loopback -e ".$file_txt."";//dd($command);//gpg-v2
        exec($command, $output, $returnVar);

        // Log output and returnVar
        //error_log("Output: " . implode("\n", $output));
        //error_log("Return Var: " . $returnVar);

        $file_gpg = config('services.ispeks.local_folder_in').$nama_fail.'.gpg';
        $data_gpg = file_get_contents($file_gpg);

        return $data_gpg;

    }

    public function file_decryption_gnupg($nama_fail_gpg)
    {
        $nama_fail_txt = Str::replace('.gpg', '', $nama_fail_gpg).".txt";
        
        $local_file_gpg = config('services.ispeks.local_folder_out').$nama_fail_gpg;//dd($local_file_gpg);
        $local_file_txt = config('services.ispeks.local_folder_out').'txt/'.$nama_fail_txt;

        $passphrase = config('services.ispeks.passphrase'); 
        $private_key_id = config('services.ispeks.private_key_id'); 
        
        $command = "gpg --pinentry-mode=loopback --passphrase ".$passphrase." --yes --output ".$local_file_txt." -d ".$local_file_gpg;//gpg-v2
        exec($command, $output, $returnVar);

        // Log output and returnVar
        //error_log("Output: " . implode("\n", $output));
        //error_log("Return Var: " . $returnVar);

        return $nama_fail_txt;

    }

}
