<?php

Import::php("OpenM-Controller.client.OpenM_RESTControllerClient");
Import::php("util.http.OpenM_URL");
Import::php("util.HashtableString");
Import::php("util.OpenM_Log");

/**
 * OpenM_RESTControllerClient_JSONLocalServer localy reproduce remote api access.
 * This local server is used for dynamic application using AJAX and JSON exchange,
 * with server.
 * @package OpenM 
 * @subpackage OpenM\OpenM-Controller\client 
 * @copyright (c) 2013, www.open-miage.org
 * @license http://www.apache.org/licenses/LICENSE-2.0 Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * @link http://www.open-miage.org
 * @author Gaël Saunier
 */
class OpenM_RESTControllerClient_JSONLocalServer {

    private $api_path;
    private $sso;

    /**
     * For remote api that protected by OpenM_SSO provider, the local server,
     * has to manage SSO session.
     * @param OpenM_SSOSession $sso if remote api is protected by OpenM_SSO provider
     */
    public function __construct(OpenM_SSOSession $sso = null) {
        if ($sso != null) {
            $this->sso = $sso;
            $this->api_path = $sso->getAPIpath();
        }
    }

    /**
     * launch local server by handle http requests.
     */
    public function handle() {
        $params = array_merge($_GET, $_POST);
        $param = HashtableString::from($params, "String");

        $api = $param->get("api");
        $method = $param->get("method");

        $args = array();
        for ($i = 1; $i < $param->size(); $i++) {
            OpenM_Log::debug("Is exist arg$i ?", __CLASS__, __METHOD__, __LINE__);
            if ($param->containsKey("arg$i"))
                $args[] = OpenM_URL::decode($param->get("arg$i"));
            else
                break;
        }

        echo OpenM_RESTControllerClient::call($this->api_path, $api, $method, $args, $this->sso, true);
    }

}

?>