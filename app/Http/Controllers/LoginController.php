<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser;
use Laravel\Passport\PersonalAccessTokenResult;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
// use  GuzzleHttp\Client ;

class LoginController extends Controller
{
    protected $server;
    protected $clients;
    protected $tokens;
    protected $jwt;

    public function __construct(AuthorizationServer $server,
                                ClientRepository $clients,
                                TokenRepository $tokens,
                                JwtParser $jwt)
    {
        $this->jwt = $jwt;
        $this->tokens = $tokens;
        $this->server = $server;
        $this->clients = $clients;
    }

    protected function createRequest($client, $userId, $scopes)
    {
        return (new ServerRequest)->withParsedBody([
            'grant_type' => 'personal_access',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'user_id' => $userId,
            'scope' => $scopes,
        ]);
    }

    protected function dispatchRequestToAuthorizationServer(ServerRequest $request)
    {
        return json_decode($this->server->respondToAccessTokenRequest(
            $request, new Response
        )->getBody()->__toString(), true);
    }

    protected function findAccessToken(array $response)
    {
        return $this->tokens->find(
            $this->jwt->parse($response['access_token'])->getClaim('jti')
        );
    }

    public function make($userId, $name, $scopes)
    {
        $response = $this->dispatchRequestToAuthorizationServer(
            $this->createRequest($this->clients->personalAccessClient(), $userId, $scopes)
        );

        $token = tap($this->findAccessToken($response), function ($token) use ($userId, $name) {
            $this->tokens->save($token->forceFill([
                'user_id' => $userId,
                'name' => $name,
            ]));
        });

        return new PersonalAccessTokenResult(
            $response['access_token'], $token
        );
    }

    public function store(LoginRequest $request)
    {
      $user = new User;
      $user_access = new User;

      if (User::where('email', $request->email)->exists()){                   //проверка на существование email
            $user = DB::table('users')->where('email', $request->email)->first();
            $user_access->id = $user->id;

            if (Hash::check($request->password, $user->password)) {           //проверка совпадения пароля
                $access_token = $user_access->createToken('MyToken');

                $date_now = Carbon::now();
                $date_expires = $access_token->token->expires_at;
                $expires_in = $date_expires->diffInSeconds($date_now);        //expires_in

                $userId = $user->id;
                $name = 'MyToken';

                $req = $this->make($userId, $name, '');

               $value = array('1' => $access_token,
                              '2' => $req,
                             );

                // $value = array('token_type' => "Bearer",
                //                'expires_in' => $expires_in,
                //                'access_token' => $access_token->accessToken,
                //                'newTokens' => $req,
                //               );
                              return $value;
            } else {}
      } else {}
    }
}


// $user = new User;
// $user_access = new User;
//
// if (User::where('email', $request->email)->exists()){                   //проверка на существование email
//       $user = DB::table('users')->where('email', $request->email)->first();
//       $user_access->id = $user->id;
//
//       if (Hash::check($request->password, $user->password)) {           //проверка совпадения пароля
//           $access_token = $user_access->createToken('MyToken');
//
//           $date_now = Carbon::now();
//           $date_expires = $access_token->token->expires_at;
//           $expires_in = $date_expires->diffInSeconds($date_now);        //expires_in
//           $value = array('token_type' => "Bearer",
//                          'expires_in' => $expires_in,
//                          'access_token' => $access_token->accessToken,
//                         );
//       } else {}
//         return $value;
// } else {}

// $http = new Client;
// $response = $http->post('http://localhost/oauth/token', [
//     'form_params' => [
//         'grant_type' => 'password',
//         'client_id' => '2',
//         'client_secret' => 'dhfMsVMYWVq1tt5udbn3Xr1W6Ev876WknbAYkLjX',
//         'username' => $request->email,
//         'password' => $request->password,
//         'scope' => '',
//     ],
// ]);
// return json_decode((string) $response->getBody(), true);
