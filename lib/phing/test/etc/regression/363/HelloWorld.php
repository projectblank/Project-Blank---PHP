<?php

    /**
     * The Hello World class!
     *
     * @author Michiel Rook
     * @version $Id: HelloWorld.php,v 1.3 2010/09/03 14:33:24 alairjt@gmail.com Exp $
     * @package hello.world
     */
    class HelloWorld
    {
        public function foo($silent = true)
        {
            if ($silent) {
                return;
            }
            return 'foo';
        }

        function sayHello()
        {
            return "Hello World!";
        }
    };

?>
