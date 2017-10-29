<?php

namespace RozbehSharahi\Meedia\Command;

use Ssh\Authentication\Agent;
use Ssh\Authentication\Password;
use Ssh\Configuration;
use Ssh\Session;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;

abstract class AbstractCommand extends Command
{

    /**
     * Question helper
     *
     * @return QuestionHelper
     */
    public function getQuestionHelper()
    {
        return $this->getHelper('question');
    }

    /**
     * Get meedia configuration
     *
     * Will merge meedia-secret.json if there.
     *
     * @param string $configFile
     * @param string $secretConfigFile
     * @return \stdClass
     */
    protected function getConfiguration(string $configFile, string $secretConfigFile='meedia-secret.json')
    {
        $configuration = json_decode(file_get_contents($configFile));

        if(!empty($configuration->secretFile)) {
            $secretConfigFile = $configuration->secretFile;
        }

        // add secrets
        if (file_exists($secretConfigFile)) {
            $configuration = (object)array_replace_recursive(
                (array)$configuration,
                (array)json_decode(file_get_contents($secretConfigFile))
            );
        }

        return $configuration;
    }

    /**
     * @param $configuration
     * @return Session
     */
    protected function getSsh($configuration)
    {
        return new Session(
            new Configuration($configuration->host, $configuration->port ?? 22),
            $this->getAuthentication($configuration)
        );
    }

    /**
     * Get authentication
     *
     * Will get the right form of authentication by configuration
     *
     * @param $configuration
     * @return Agent|Password
     */
    protected function getAuthentication($configuration)
    {
        if (!empty($configuration->password)) {
            return new Password($configuration->user, $configuration->password);
        }

        return new Agent($configuration->user);
    }


}