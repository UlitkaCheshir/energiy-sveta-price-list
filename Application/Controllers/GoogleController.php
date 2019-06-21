<?php
/**
 * Created by PhpStorm.
 * User: ULITKA
 * Date: 03.04.2019
 * Time: 12:05
 */

namespace Application\Controllers;
use Application\Service\UserService;



class GoogleController extends BaseController
{

    public function AuthAction(){

        $userService = new UserService();

        $client = new \Google_Client();

        $oauth_credentials = __DIR__ . '/../oauth-credentials.json';

        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'].URL."/auth";
        var_dump($_SESSION);
        $client->setAuthConfig($oauth_credentials);
        $client->setRedirectUri($redirect_uri);
        $client->addScope(['https://www.googleapis.com/auth/userinfo.email',"https://www.googleapis.com/auth/userinfo.profile"]);

        $authUrl = null;

        $code = $this->request->GetGetValue("code");

        if (isset($code))
        {
            $token = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($token);

            // store in the session also
            $_SESSION['upload_token_google'] = $token;

            // redirect back to the example
            header('Location: ' . $redirect_uri);
        }//if

        if (!empty($_SESSION['upload_token_google'])) {

            $client->setAccessToken($_SESSION['upload_token_google']);
            if ($client->isAccessTokenExpired()) {
                unset($_SESSION['upload_token_google']);
            }//if

        } //else
        else {
            $authUrl = $client->createAuthUrl();
//            file_get_contents($authUrl);
        }//else
        var_dump($authUrl);


        if(!empty($_SESSION['upload_token_google']['access_token'])){
            $query =  "https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=".$_SESSION['upload_token_google']['access_token'];
            $json = file_get_contents($query);
            $userInfoArray = json_decode($json,true);

            var_dump($userInfoArray);

            $findUser = null;
            if(!empty($userInfoArray)){
                $findUser = $userService->GetUser("bla1");

                if(!$findUser){
                    $idUser = $userService->AddUser();

                    $findUser = $userService->GetUser('$idUser');
                }//if

            }//if


            var_dump("idUser");
//            var_dump($idUser);

        }//if

        var_dump($_POST);

        ?>


        <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <title>Google Powered Login Form | Tutorialzine Demo</title>

    </head>

    <body>

		<h1>Login Form</h1>
        <div id="main">
            <?php
        if(empty($_SESSION['upload_token'])):?>
            	<a href="<?= $authUrl?>" class="googleLoginButton">Sign in with Google</a>
        <?php else:?>
            <a href="<?php echo $_SERVER['HTTP_HOST'].URL."/logout"?>" class="googleLoginButton">Logout</a>
        <?php endif;?>

        </div>


    </body>
</html>
<?php
    }

    public function LogoutAction()
    {

        if(!empty($_SESSION['upload_token_google'])){
            unset($_SESSION['upload_token_google']);
        }//if

    }
}