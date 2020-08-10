**kemana/module-labelmanager**

#### Description:
New label manager has been implemented to work template independent. Multi 
store support has been fixed and Issues Multiple labels for single product 
has been implemented. With the current update you will able to make sale 
label appear automatically for special priced products or catalog price rules 
discounted products. 

#### Installation
Dependencies:
```sh
kemana/module-core
```

#### How to Install
Add repository to composer.json

using ssh
```sh
composer config repositories.kemana-labelmanager git@git.kemana.com:kemana-dev/accelerator-features/kemana_labelmanager.git
```
using https
```sh
composer config repositories.kemana-labelmanager git https://git.kemana.com/kemana-dev/accelerator-features/kemana_labelmanager.git
```

Install module
```sh
composer require kemana/module-labelmanager:100.35.0
```

After installation by either means, enable the module by running following commands:
```sh
$ php bin/magento module:enable Kemana_Labelmanager --clear-static-content
$ php bin/magento setup:upgrade
```

#### Changelog
[100.35.0] Initial module creation
