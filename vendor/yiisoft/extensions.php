<?php

$vendorDir = dirname(__DIR__);

return array (
  'yiisoft/yii2-bootstrap' => 
  array (
    'name' => 'yiisoft/yii2-bootstrap',
    'version' => '2.0.6.0',
    'alias' => 
    array (
      '@yii/bootstrap' => $vendorDir . '/yiisoft/yii2-bootstrap',
    ),
  ),
  'yiisoft/yii2-httpclient' => 
  array (
    'name' => 'yiisoft/yii2-httpclient',
    'version' => '2.0.4.0',
    'alias' => 
    array (
      '@yii/httpclient' => $vendorDir . '/yiisoft/yii2-httpclient',
    ),
  ),
  'yiisoft/yii2-authclient' => 
  array (
    'name' => 'yiisoft/yii2-authclient',
    'version' => '2.1.3.0',
    'alias' => 
    array (
      '@yii/authclient' => $vendorDir . '/yiisoft/yii2-authclient',
    ),
  ),
  'yiisoft/yii2-swiftmailer' => 
  array (
    'name' => 'yiisoft/yii2-swiftmailer',
    'version' => '2.1.0.0',
    'alias' => 
    array (
      '@yii/swiftmailer' => $vendorDir . '/yiisoft/yii2-swiftmailer',
    ),
  ),
  'dektrium/yii2-user' => 
  array (
    'name' => 'dektrium/yii2-user',
    'version' => '0.9.12.0',
    'alias' => 
    array (
      '@dektrium/user' => $vendorDir . '/dektrium/yii2-user',
    ),
    'bootstrap' => 'dektrium\\user\\Bootstrap',
  ),
);
