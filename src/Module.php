<?php
namespace canis\userManager;

use Yii;
use yii\base\Application;
use yii\base\Event;
use canis\caching\Cacher;

/**
 * Module [[@doctodo class_description:canis\broadcaster\Module]].
 *
 * @author Jacob Morrison <email@ofjacob.com>
 */
class Module extends \yii\base\Module
{
    public $friendlyUrl = 'user';
    protected $_twoFactorEnabled = true;

    public function setTwoFactorEnabled($enabled)
    {
        $this->_twoFactorEnabled = $enabled;
    }

    public function getTwoFactorEnabled()
    {
        if (!$this->_twoFactorEnabled) {
            return false;
        }
        return class_exists('RobThree\Auth\TwoFactorAuth');
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $app = Yii::$app;
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                $this->friendlyUrl . '/<controller:[\w\-]+>' => $this->id . '/<controller>/index',
                $this->friendlyUrl . '/<controller:[\w\-]+>/<action:[\w\-]+>' => $this->id . '/<controller>/<action>',
            ], false);

          $controllers = [
              'manage' => controllers\ManageController::className(),
              'admin' => controllers\AdminController::className()
          ];
          foreach ($controllers as $id => $controller) {
              if (!$controller) { continue; }
              $this->controllerMap[$id] = $controller;
          }
        }
   }

   public function isPasswordManagementAvailable()
   {
      if (Yii::$app->user->isGuest) {
        return false;
      }
      $activeIdentity = Yii::$app->user->getIdentity()->getActiveIdentity();
      if (!empty($activeIdentity)) {
        return false;
      }
      return true;
   }
}
