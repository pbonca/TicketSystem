<?php
namespace frontend\controllers;
use common\models\ViewComment;
use common\models\CommonUser;
use common\models\Ticket;
use DateTime;
use frontend\models\ChangeDataForm;
use frontend\models\SubmitTicketForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\SignupForm;
use yii\web\Response;

/**
 * Site controller
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
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                        //? => vendég
                    ],
                    [
                        'actions' => ['logout', 'change-data', 'profile', 'submit-ticket', 'list-tickets',
                        'view-comments', 'close-ticket'],
                        'allow' => true,
                        'roles' => ['@'],
                        //@ => bejelentkezett user
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                        //?,@ => mindneki
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
        return $this->render('index');
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

    public function actionListTickets()
    {
        $tickets = Ticket::find()->andWhere(['author_id' => Yii::$app->user->id]);
        $provider = new ActiveDataProvider([
            'query' => $tickets,
            'pagination' => [
                'pageSize' => 10,
            ],
           'sort' => [
               'defaultOrder' => [
                   'id' => SORT_ASC,
               ],
           ],
        ]);
        $provider->setSort([
            'attributes' => [
                'status' => [
                    'asc' => ['status' => SORT_ASC],
                    'desc' => ['status' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'modification_date' => [
                    'asc' => ['modification_date' => SORT_ASC],
                    'desc' => ['modification_date' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
            ],
            'defaultOrder' => [
                'modification_date' => SORT_ASC
            ]
        ]);

        return $this->render('listTickets', ['provider' => $provider]);
    }

    public function actionCloseTicket()
    {
        $ticket_id = Yii::$app->request->post('ticketId');
        if ($ticket_id == null) {
            return $this->goHome();
        }
        $ticket = Ticket::find()->ofId($ticket_id)->one();
        if ($ticket === null) {
            Yii::$app->session->setFlash('error', 'There is no such ticket!');

            return $this->goHome();
        }
        $ticket->status = Ticket::STATUS_CLOSED;
        $ticket->modification_date = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['res' => $ticket->save()];
    }

    public function actionViewComments()
    {
        $ticket_id = Yii::$app->request->get('id');
        $model = new ViewComment();
        $ticket = Ticket::find()->ofId($ticket_id)->ofuserId(Yii::$app->user->id)->one();
        if ($ticket === null) {
            return $this->goHome();
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->author_id = Yii::$app->user->identity->id;
            $model->ticket_id = $ticket_id;
            $model->creation_date = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
            $ticket->modification_date = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
            if ($ticket->status == Ticket::STATUS_CLOSED) {
                $ticket->status = Ticket::STATUS_ACTIVE;
                $ticket->save();
            }
            $model->save();
        }
        $model->content = null;
        $comments = ViewComment::find()->with('author')->ofTicketId($ticket_id)->all();

        return $this->render('viewComments', ['model' => $model, 'ticket' => $ticket, 'comments' => $comments]);
    }

    /**
     * Changes the personal data
     * @return string|\yii\web\Response
     */
    public function actionChangeData()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new ChangeDataForm();
        /** @var CommonUser $user */
        $user = Yii::$app->user->identity;
        $model->fillFrom($user);
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->newPassword)){
                $model->scenario = ChangeDataForm::SCENARIO_CHANGE_PASSWORD;
            }
            if ($model->validate()) {
                $user = $model->fillTo($user);
                if($user->save()) {
                    Yii::$app->session->setFlash('success', 'Personal data changed successfully!');

                    return $this->render('changeData', ['model' => $model]);
                }
            }
        }

        return $this->render('changeData', ['model' => $model]);
    }


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', 'You logged out!');

        return $this->goHome();
    }


    /**
     * Displays profile page.
     *
     * @return mixed
     */
    public function actionProfile()
    {
        if (!Yii::$app->user->isGuest) {
            $model = CommonUser::find()->ofId(Yii::$app->user->id)->one();
            if($model === null) {
                Yii::$app->session->setFlash('error', 'Unknown user');
                $this->goHome();
            }

            return $this->render('profile', ['model' => $model->printMyData()]);
        }
        return $this->render('index');
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
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');

            return $this->redirect('login');
        }


        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSubmitTicket()
    {
        $model = new SubmitTicketForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->submit();
            Yii::$app->session->setFlash('success', 'The ticket submitted successfully!');

                return $this->render('index', ['model' => $model]);
        }
        return $this->render('submitTicket', [
            'model' => $model,
        ]);
    }
}
