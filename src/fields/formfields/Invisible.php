<?php

namespace barrelstrength\sproutforms\fields\formfields;

use Craft;
use craft\base\ElementInterface;
use craft\helpers\Template as TemplateHelper;
use craft\base\PreviewableFieldInterface;
use barrelstrength\sproutforms\base\FormField;

class Invisible extends FormField implements PreviewableFieldInterface
{
    /**
     * @var bool
     */
    public $allowRequired = false;

    /**
     * @var bool
     */
    public $allowEdits;

    /**
     * @var bool
     */
    public $hideValue;

    /**
     * @var string|null
     */
    public $value;

    public static function displayName(): string
    {
        return Craft::t('sprout-forms', 'Invisible');
    }

    /**
     * @return bool
     */
    public function isPlainInput()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getSvgIconPath()
    {
        return '@sproutbaseicons/eye-slash.svg';
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'sprout-forms/_components/fields/formfields/invisible/settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $name = $this->handle;
        $inputId = Craft::$app->getView()->formatInputId($name);
        $namespaceInputId = Craft::$app->getView()->namespaceInputId($inputId);

        return Craft::$app->getView()->renderTemplate('sprout-base-fields/_components/fields/formfields/invisible/input',
            [
                'id' => $namespaceInputId,
                'name' => $name,
                'value' => $value,
                'field' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getExampleInputHtml()
    {
        return Craft::$app->getView()->renderTemplate('sprout-forms/_components/fields/formfields/invisible/example',
            [
                'field' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getFrontEndInputHtml($value, array $renderingOptions = null): string
    {
        Craft::$app->getSession()->set($this->handle, $this->value);

        $html = '<input type="hidden" name="'.$this->handle.'">';

        return TemplateHelper::raw($html);
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        $session = Craft::$app->getSession()->get($this->handle);
        $sessionValue = $session ?: '';

        return Craft::$app->view->renderObjectTemplate($sessionValue, parent::getFieldVariables());
    }

    /**
     * @inheritdoc
     */
    public function getTableAttributeHtml($value, ElementInterface $element): string
    {
        $hiddenValue = '';

        if ($value != '') {
            $hiddenValue = $this->hideValue ? '&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;' : $value;
        }

        return $hiddenValue;
    }
}
