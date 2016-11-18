# qphp
A rapid development of the PHP framework , it was use composer plugin 

### install step :
```sh
cd qphp
composer install
```

### Controller code :

```
<?php

namespace Controller;

use \Uri\Utils;
/**
* example for API 
*/
class api extends Utils
{
    
    #$config is your config.inc.php's configration
    function __construct($config)
    {
        # init some ORM opration such as mysql redis mongodb ,you can found them from github or composer support

    }
    public function index(){
        echo __CLASS__;
    }

    public function login(){
        if($this->Get('username') && $this->Get('password')){
            $_SESSION['username'] = $_GET['username'];
            echo json_encode(array('msg'=>'success'));
            return;
        }
        echo json_encode(array('msg'=>'login fail'));
    }
}
```

View code :

``` #HTML + PHP code 


< html>
< body>
< h1>hello world</h 1>
< /body>
< /html>
<?php echo phpinfo(); ?>
```

### it is easy support API request . such as android's server or IOS's server 
