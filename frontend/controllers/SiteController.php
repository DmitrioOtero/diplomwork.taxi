<?php
namespace frontend\controllers;

use backend\models\MyReservationsSearch;
use backend\models\Reservation;
use backend\models\ReservationSearch;
use backend\models\Rout;
use backend\models\Trip;
use backend\models\TripSearch;
use backend\models\UsersTripSearch;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
echo "<script>console.log('{$this->username}');</script>";
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'model' => new ReservationSearch(),
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionSearch()
    {
        $searchModel = new ReservationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Trip();
        $model->user_id = Yii::$app->user->id;
        $post = Yii::$app->request->post();
        if ($model->load($post) and $model->validate() and $model->save()) {  // нажали сохранить
            $routs = [];
            $break_number = 0;
            for($place_number = 1; $place_number < count($post["Rout"]); $place_number++) {
                if (
                    empty($post["Rout"][$place_number]["place"]) or
                    empty($post["Rout"][$place_number]["price"]) or
                    empty($post["Rout"][$place_number]["number_of_seats"])
                ) {
                    break;
                }
                $break_number = $place_number;
            }
            for($place_number = 1; $place_number <= $break_number; $place_number++) {
                $routs[$place_number] = new Rout();
                $routs[$place_number]->trip_id = $model->id;
                $routs[$place_number]->from = $post["Rout"][$place_number - 1]["place"];
                $routs[$place_number]->to = $post["Rout"][$place_number]["place"];
                $routs[$place_number]->sort = $place_number - 1;
                $routs[$place_number]->price = $post["Rout"][$place_number]["price"];
                $routs[$place_number]->number_of_seats = $post["Rout"][$place_number]["number_of_seats"];
                if (!$routs[$place_number]->save()) {
                    print_r($routs);
                    unset($routs[$place_number]);
                    foreach ($routs as $rout) {
                        $rout->delete();
                    }
                    $model->delete();
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
            return $this->redirect(['trips']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionSuccess()
    {
        return $this->render('success');
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionReservation($trip_id, $from_id, $to_id, $number_of_seats)
    {
        $model = new Reservation();
        $model->trip_id = $trip_id;
        $model->rout_from_id = $from_id;
        $model->rout_to_id = $to_id;
        $model->number_of_seats = $number_of_seats;
        $post = Yii::$app->request->post();
        if ($model->load($post) and $model->validate() and $model->save()) {//сохранили
            return $this->redirect(['success']);
        }

        return $this->render('reservation', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionMyReservations($trip_id)
    {
        $searchModel = new MyReservationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $trip_id);
        $trip = Trip::findOne($trip_id);
        $routs_from = [];
        $routs_to = [];
        foreach ($trip->rout as $rout) {
            $routs_from[$rout->id] = $rout->from;
            $routs_to[$rout->id] = $rout->to;
        }

        return $this->render('my-reservations', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'trip' => $trip,
            'routs_from' => $routs_from,
            'routs_to' => $routs_to,
        ]);
    }

    public function actionDeleteReservation($id)
    {
        $model = Reservation::findOne($id);
        $trip_id = $model->trip_id;
        if ($model) {
            $model->delete();
        }

        return $this->redirect(['my-reservations?trip_id=' . $trip_id]);
    }

    public function actionDeleteTrip($id)
    {
        $model = Trip::findOne($id);
        if ($model) {
            $model->delete();
        }

        return $this->redirect(['trips']);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionTrips()
    {
        $searchModel = new TripSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('trips', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Регистрация прошла успешно!');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
