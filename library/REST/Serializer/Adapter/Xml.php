<?php
/**
 * Puvoo
 * http://www.puvoo.com
 *
 * NOTICE OF LICENSE
 *
 * Copyright (c) 2011
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@puvoo.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Puvoo to newer
 * versions in the future. If you wish to customize Puvoo for your
 * needs please refer to http://www.puvoo.com for more information.
 */
 
class REST_Serializer_Adapter_Xml extends Zend_Serializer_Adapter_AdapterAbstract
{
    /**
     * @var array Default options
     */
    protected $_options = array(
        'rootNode' => 'response',
    );

    /**
     * Serialize PHP value to XML
     *
     * @param  mixed $value
     * @param  array $opts
     * @return string
     * @throws Zend_Serializer_Exception on XML encoding exception
     */
    public function serialize($value, array $opts = array())
    {
        $opts = $opts + $this->_options;

        try
        {
            $dom = new DOMDocument;
            $root = $dom->appendChild($dom->createElement($opts['rootNode']));
            $this->createNodes($dom, $value, $root, false);
            return $dom->saveXml();
        }
        catch (Exception $e)
        {
            require_once 'Zend/Serializer/Exception.php';
            throw new Zend_Serializer_Exception('Serialization failed', 0, $e);
        }
    }

    /**
     * Deserialize XML to PHP value
     *
     * @param  string $json
     * @param  array $opts
     * @return mixed
     */
    public function unserialize($xml, array $opts = array())
    {
        try
        {
            Zend_Json::fromXml($xml);
            return (array) Zend_Json::decode($json, Zend_Json::TYPE_OBJECT);
        }
        catch (Exception $e)
        {
            require_once 'Zend/Serializer/Exception.php';
            throw new Zend_Serializer_Exception('Unserialization failed by previous error', 0, $e);
        }
    }

    private function createNodes($dom, $data, &$parent, $name = false)
    {
        switch (gettype($data))
        {
            case 'string':
            case 'integer':
            case 'double':
                $parent->appendChild($dom->createTextNode($data));
                break;

            case 'boolean':
                switch ($data)
                {
                    case true:
                        $value = 'true';
                        break;

                    case false:
                        $value = 'false';
                        break;
                }

                $parent->appendChild($dom->createTextNode($value));
                break;

            case 'object':
            case 'array':
                foreach ($data as $key => $value)
                {
                    if (is_object($value) and $value instanceOf DOMDocument and !empty($value->firstChild))
                    {
                        $node = $dom->importNode($value->firstChild, true);
                        $parent->appendChild($node);
                    }
                    else
                    {
                        if (gettype($value) == 'array' and !is_numeric($key))
                        {
                            $this->createNodes($dom, $value, $parent, $key);
                        }
                        else
                        {
                            if (is_numeric($key))
                            {
                                if ($name !== false)
                                {
                                    $key = $name;
                                }
                                else
                                {
                                    $key = $parent->tagName;
                                }
                            }

                            $child = $parent->appendChild($dom->createElement($key));
                            $this->createNodes($dom, $value, $child);
                        }
                     }

                }

                break;
        }
    }
}
