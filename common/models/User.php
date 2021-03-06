<?php

namespace common\models;

use cheatsheet\Time;
use common\commands\command\AddToTimelineCommand;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use common\behaviors\GuidBehavior;
use common\behaviors\AuditBehavior;
use rmrevin\yii\module\Comments\interfaces\CommentatorInterface;
use yii\helpers\Html;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $full_name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $publicIdentity
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $logged_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface, CommentatorInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const ROLE_USER = 'user';
    const ROLE_COMMAND_MAKER = 'command-maker';
    const ROLE_TASK_RUNNER = 'task-runner';
    const ROLE_ADMINISTRATOR = 'administrator';
    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_AFTER_LOGIN = 'afterLogin';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
            'auth_key' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'auth_key'
                ],
                'value' => Yii::$app->getSecurity()->generateRandomString()
            ],
            GuidBehavior::className(),
            'audit' => [
                'class' => AuditBehavior::className(),
                'events' => [],
                'dataFunction' => function($model) {
            return $this->getAuditAttributes($model->oldAttributes);
        }
            ]
        ];
    }

    private function getAuditAttributes($attributes) {
        if (isset($attributes['password'])) {
            unset($attributes['password']);
        }
        if (isset($attributes['password_hash'])) {
            unset($attributes['password_hash']);
        }
        if (isset($attributes['password_reset_token'])) {
            unset($attributes['password_reset_token']);
        }
        if (isset($attributes['auth_key'])) {
            unset($attributes['auth_key']);
        }
        return $attributes;
    }

    /**
     * @return array
     */
    public function scenarios() {
        return ArrayHelper::merge(
                        parent::scenarios(), [
                    'oauth_create' => [
                        'oauth_client', 'oauth_client_user_id', 'email', 'username', '!status'
                    ]
                        ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'email'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['full_name', 'locale'], 'string', 'max' => 255],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => Yii::t('common', 'Username'),
            'full_name' => Yii::t('common', 'Full Name'),
            'email' => Yii::t('common', 'E-mail'),
            'status' => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created at'),
            'updated_at' => Yii::t('common', 'Updated at'),
            'logged_at' => Yii::t('common', 'Last login'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * Detect audit events when user is updated
     */
    public function auditUpdate() {
        $attributes = $this->getAuditAttributes($this->oldAttributes);
        if (isset($attributes['logged_at']) && $attributes['logged_at'] != $this->logged_at) {
            $attributes['username'] = $this->getName();
            AuditRecord::create('user-signedin', 'user', $this->id, $attributes);
        } else {
            // user updated
            AuditRecord::create('user-updated', 'user', $this->id, $attributes);
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username or email
     *
     * @param string $login
     * @return static|null
     */
    public static function findByLogin($login) {
        return static::findOne([
                    'and',
                    ['or', ['username' => $login], ['email' => $login]],
                    'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        $expire = Time::SECONDS_IN_A_DAY;
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * Returns user statuses list
     * @param mixed $status
     * @return array|mixed
     */
    public static function getStatuses($status = false) {
        $statuses = [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_DELETED => Yii::t('common', 'Deleted')
        ];
        return $status !== false ? ArrayHelper::getValue($statuses, $status) : $statuses;
    }

    /**
     * Creates user profile and application event
     * @param array $profileData
     */
    public function afterSignup(array $profileData = []) {
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'event' => 'signup',
            'data' => [
                'publicIdentity' => $this->getPublicIdentity(),
                'userId' => $this->getId(),
                'created_at' => $this->created_at
            ]
        ]));
        //$profile = new UserProfile();
        //$profile->locale = Yii::$app->language;
        //$profile->load($profileData, '');
        //$this->link('userProfile', $profile);
        $this->trigger(self::EVENT_AFTER_SIGNUP);
        // Default role
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole(User::ROLE_USER), $this->getId());
    }

    public function afterSave($insert, $changedAttributes) {
        \d($insert, $changedAttributes);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return string
     */
    public function getPublicIdentity() {
        if (strlen($this->full_name)) {
            return $this->full_name;
        }
        if ($this->username) {
            return $this->username;
        }
        return $this->email;
    }

    /**
     * Full name or username
     * @return type
     */
    public function getName() {
        return $this->full_name ? $this->full_name : $this->username;
    }

    public function getCommentatorName(){
        return $this->getName();
    }

    public function getCommentatorAvatar() {
        return Yii::$app->urlManager->createUrl('/img/user.jpg');
    }

    public function getCommentatorUrl() {
        return null;
    }

}
