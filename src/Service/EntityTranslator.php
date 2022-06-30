<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class EntityTranslator
{
    /** @var string[] */
    private array $allLocales;

    public function __construct(RequestStack $request)
    {
        $this->allLocales = ['Cs', 'En'];
        $this->selectedViewLocale = 'Cs';
        if ($request->getCurrentRequest()) {
            $this->selectedViewLocale = ucfirst($request->getCurrentRequest()->get('_locale', 'cs'));
        }
    }

    public function getAllLocales()
    {
        return $this->allLocales;
    }

    public function createEmptyAllLocalesJson()
    {
        $data = [];
        foreach ($this->allLocales as $locale) {
            $data[$locale] = '';
        }
        return json_encode($data);
    }

    public function jsonHtml(string $json)
    {
        $locales = json_decode($json);
        $response = '';

        foreach ($locales as $locale => $data) {
            $response .= '<b>' . mb_strtoupper($locale) . '</b>: ' . $data . '<br>';
        }

        return $response;
    }

    public function read(string $data)
    {
        $locales = json_decode($data, true);

        $result = '';
        foreach ($locales as $row) {
            if ($row) {
                $result = $row;
            }
        }

        return $locales[$this->selectedViewLocale] ?: $result;
    }

    public function map($form, $entity, $varsLang, $vars = null)
    {
        foreach ($varsLang as $var) {
            $row = [];

            foreach ($this->allLocales as $locale) {
                $row[$locale] = $form->get("{$var}{$locale}")->getData();
            }

            $setter = "set" . ucfirst($var);
            $entity->$setter(json_encode($row));
        }

        if (!is_null($vars)) {
            foreach ($vars as $var) {
                $row = $form->get("{$var}")->getData();

                $setter = "set" . ucfirst($var);
                $entity->$setter($row);
            }
        }

        return $entity;
    }

    public function unmap($entity, $varsLang, $vars = null)
    {
        $data = [];

        foreach ($varsLang as $var) {
            $getter = "get" . ucfirst($var);
            $stringData = $entity->$getter() ?? $this->createEmptyAllLocalesJson();
            $data[$var] = json_decode($stringData);

            foreach ($data[$var] as $key => $value) {
                $data["{$var}{$key}"] = $value;
            }

            unset($data[$var]);
        }

        if (!is_null($vars)) {
            foreach ($vars as $var) {
                $getter = "get" . ucfirst($var);
                $data[$var] = $entity->$getter();
            }
        }

        return $data;
    }

}