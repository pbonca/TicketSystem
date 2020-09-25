<?php
namespace backend\controllers;

use backend\models\ChangeUserData;
use common\models\CommonUser;
use common\models\Ticket;
use common\models\ViewComment;
use DateTime;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'users', 'tickets', 'delete', 'update', 'list-tickets',
                            'view-comments', 'assign-to-admin', 'close-ticket', 'view', 'list-user-tickets'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {

            return $this->goHome();
        }
        $this->layout = 'blank';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->adminLogin()) {

            return $this->goBack();
        } else {
            $model->password = '';
            $model->email = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    public function actionAssignToAdmin()
    {
        if (Yii::$app->user->isGuest) {

            return $this->goHome();
        }
        $admin = Yii::$app->user->identity;
        $ticket_id = Yii::$app->request->get('id');
        $ticket = Ticket::find()->ofId($ticket_id)->one();
        if ($ticket === null) {

            return $this->goBack();
        }
        $ticket->admin_id = $admin->id;
        $ticket->modification_date = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
        if ($ticket->save()) {
            Yii::$app->session->setFlash('success', 'You successfully assigned this ticket to yourself!');

            return $this->goHome();
        } else {
            Yii::$app->session->setFlash('error', 'Something went wrong');
        }

        return $this->goHome();
    }

    public function actionViewComments()
    {
        $ticket_id = Yii::$app->request->get('id');
        $model = new ViewComment();
        $ticket = Ticket::find()->ofId($ticket_id)->one();
        if ($ticket === null) {

            return $this->goHome();
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->author_id = Yii::$app->user->identity->id;
            $model->ticket_id = $ticket_id;
            $model->creation_date = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
            $ticket->modification_date = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
            if ($model->validate()) {
                if ($ticket->status == Ticket::STATUS_CLOSED) {
                    $ticket->status = Ticket::STATUS_ACTIVE;
                    $ticket->save();
                }
                $model->save();
            }
        }
        $model->content = null;
        $comments = ViewComment::find()->with('author')->ofTicketId($ticket_id)->all();

        return $this->render('viewComments', ['model' => $model, 'ticket' => $ticket, 'comments' => $comments]);
    }

    public function actionListUserTickets()
    {
        $user_id = Yii::$app->request->get('id');
        $tickets = Ticket::find()->ofuserId($user_id);
        if ($tickets === null) {
            Yii::$app->session->setFlash('error', 'There is no such user!');

            return $this->goBack();
        }
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

        return $this->render('listTickets', ['provider' => $provider]);
    }

    public function actionCloseTicket()
    {
        $ticket_id = Yii::$app->request->post('ticketId');
        $ticket = Ticket::find()->ofId($ticket_id)->one();
        if ($ticket === null) {
            Yii::$app->session->setFlash('error', 'There is no such ticket!');

            $this->goHome();
        }
        $ticket->status = Ticket::STATUS_CLOSED;
        $ticket->modification_date = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['res' => $ticket->save()];
    }


    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        $user = CommonUser::find()->ofId($id)->one();
        if ($user === null) {
            Yii::$app->session->setFlash('error', "Couldn't find user");

            return $this->goHome();
        }
        $model = new ChangeUserData();
        $model->fillFrom($user);
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->password)) {
                $model->scenario = ChangeUserData::SCENARIO_CHANGE_PASSWORD;
            }
            if ($model->validate()) {
                $user = $model->fillTo($user);
                if ($user->save()) {
                    Yii::$app->session->setFlash('success', "User's data successfully updated!");

                    return $this->redirect('users');
                } else {

                    return Yii::$app->session->setFlash('error','Something failed');
                }
            }
        }

        return $this->render('changeUserData', ['model' => $model]);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');
        $user = CommonUser::find()->ofId($id)->one();
        if ($user === null) {
            Yii::$app->session->setFlash('error', 'There is no such user');
        } else {
            if ($user == Yii::$app->user->identity) {
                Yii::$app->session->setFlash('error', "You can NOT delete yourself!");
                return $this->redirect(['site/users']);
            }
            if ($user->delete() !== false ) {
                Yii::$app->session->setFlash('success', 'The user is gone!');
                $itsTickets = Ticket::find()->ofAdminId($user->id)->all();
                if ($itsTickets === null ) {

                    return $this->goHome();
                }
                foreach ($itsTickets as $itsTicket) {
                    $itsTicket->admin_id = Ticket::NULL;
                    $itsTicket->save();
                }
            } else {
                Yii::$app->session->setFlash('error', 'Failed to delete');
            }
        }
        $users = CommonUser::find();
        $provider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('users', ['provider' => $provider]);
    }


    public function actionUsers()
    {
        $users = CommonUser::find();
        $provider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('users', ['provider' => $provider]);
    }

    public function actionView()
    {
        $user_id = Yii::$app->request->get('id');
        $model = CommonUser::find()->ofId($user_id)->one();
        //If the user with this id is not in the database
        if ($model === null) {
            Yii::$app->session->setFlash('error', 'Unknown user ID');

            return $this->render('users');
        }

        return $this->render('profile', ['model' => $model]);
    }


    public function actionListTickets()
    {
        $tickets = Ticket::find();
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

        return $this->render('listTickets', ['provider' => $provider]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
