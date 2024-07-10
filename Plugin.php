<?php

use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;
use Typecho\Widget\Helper\Form\Element\Text;
use Typecho\Widget\Helper\Form\Element\Checkbox;
use Widget\Options;
use Typecho\Plugin;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * PostChat
 *
 * @package PostChat
 * @author 张洪Heo
 * @version 1.0.0
 * @link http://zhheo.com/
 */
class PostChat_Plugin implements PluginInterface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     */
    public static function activate()
    {
        try {
            Plugin::factory('Widget_Archive')->beforeRender = __CLASS__ . '::insertPostContentWrapper';
            Plugin::factory('Widget_Archive')->footer = __CLASS__ . '::insertFooterScript';
        } catch (Exception $e) {
            return _t('激活失败: ') . $e->getMessage();
        }
        return _t('插件已激活');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     */
    public static function deactivate()
    {
        return _t('插件已禁用');
    }

    /**
     * 获取插件配置面板
     *
     * @param Form $form 配置面板
     */
    public static function config(Form $form)
    {
        $form->addInput(new Text('key', NULL, '70b649f150276f289d1025508f60c5f58a', _t('账户KEY'), _t('使用PostChat的用户请前往 https://ai.tianli0.top/ 获取 KEY，只使用文章摘要的用户前往 https://summary.zhheo.com/ 获取 KEY 。示例的Key不支持文章摘要和自定义的知识库问答，但可以使用作者的知识库对话')));

        $form->addInput(new Checkbox('enableSummary', array('true' => '开启文章摘要'), array('true'), _t('开启文章摘要')));
        $form->addInput(new Text('postSelector', NULL, '#postchat_postcontent', _t('文章选择器'), _t('文章选择器，用于选择文章内容。如果没有正常显示摘要，你需要访问 https://postsummary.zhheo.com/theme/custom.html#%E8%8E%B7%E5%8F%96tianligpt-postselector 学习获取，也可以联系 zhheo@qq.com 发送你的网站地址后获取')));
        $form->addInput(new Text('title', NULL, '文章摘要', _t('摘要标题'), _t('摘要标题，用于显示在摘要顶部的自定义内容')));
        $form->addInput(new Text('summaryStyle', NULL, 'https://ai.tianli0.top/static/public/postChatUser_summary.min.css', _t('摘要样式css'), _t('摘要样式css地址，如果你需要自定义摘要的css样式，可以自行修改。')));
        $form->addInput(new Text('postURL', NULL, '*/archives/*', _t('文章路由'), _t('在符合url条件的网页执行文章摘要功能，你需要根据【设置】->【永久链接】的设置进行配置此选项，支持通配符和斜线开头结尾的正则表达式。详见：https://postchat.zhheo.com/summary.html#tianligpt-posturl')));
        $form->addInput(new Text('blacklist', NULL, '', _t('黑名单'), _t('填写相关的json地址，帮助文档：https://postsummary.zhheo.com/parameters.html#tianligpt-blacklist')));
        $form->addInput(new Text('wordLimit', NULL, '1000', _t('字数限制'), _t('危险操作！如果没有在文章摘要中开启url绑定，更改此变量损失已消耗过的key，因为你提交的内容发生了变化。（PostChat用户无影响，因为摘要数量是无限的）可以设置提交的字数限制，默认为1000字。帮助文档：https://postsummary.zhheo.com/parameters.html#tianligpt-wordlimit')));
        $form->addInput(new Checkbox('typingAnimate', array('true' => '打字动画效果'), array('true'), _t('打字动画效果')));

        $form->addInput(new Checkbox('enableAI', array('true' => '开启PostChat智能对话'), array('true'), _t('开启PostChat智能对话')));
        $form->addInput(new Text('backgroundColor', NULL, '#3e86f6', _t('背景颜色'), _t('调整按钮背景色彩')));
        $form->addInput(new Text('fill', NULL, '#FFFFFF', _t('填充颜色'), _t('调整按钮里面图标的颜色')));
        $form->addInput(new Text('bottom', NULL, '16px', _t('底部距离'), _t('按钮距离底部的边距')));
        $form->addInput(new Text('left', NULL, '16px', _t('左边距离'), _t('按钮距离左侧的边距，如果填写负值，则是距离右侧的边距。例如left为-3px，实际为right 3px')));
        $form->addInput(new Text('width', NULL, '44px', _t('宽度'), _t('调整按钮的宽度')));
        $form->addInput(new Text('frameWidth', NULL, '375px', _t('框架宽度'), _t('调整聊天界面框架的宽度')));
        $form->addInput(new Text('frameHeight', NULL, '600px', _t('框架高度'), _t('调整聊天界面框架的高度')));
        $form->addInput(new Checkbox('defaultInput', array('true' => '默认输入'), array('true'), _t('默认输入')));
        $form->addInput(new Checkbox('upLoadWeb', array('true' => '上传网站'), array('true'), _t('上传网站')));
        $form->addInput(new Checkbox('showInviteLink', array('true' => '显示邀请链接'), array('true'), _t('显示邀请链接')));
        $form->addInput(new Text('userTitle', NULL, 'PostChat', _t('界面标题'), _t('你要自定义的PostChat界面标题')));
        $form->addInput(new Text('userDesc', NULL, '如果你对网站的内容有任何疑问，可以来问我哦～', _t('聊天界面描述'), _t('你要自定义的PostChat聊天界面描述')));
        $form->addInput(new Checkbox('addButton', array('true' => '是否显示按钮'), array('true'), _t('是否显示按钮')));
    }

    /**
     * 个人用户的配置面板
     *
     * @param Form $form
     */
    public static function personalConfig(Form $form) {}

    public static function insertPostContentWrapper($archive)
    {
        if ($archive->is('single')) {
            $archive->content = '<div id="postchat_postcontent">' . $archive->content . '</div>';
        }
    }

    public static function insertFooterScript()
    {
        $settings = Options::alloc()->plugin('PostChat', true);

        $defaults = [
            'key' => 'default_key',
            'postSelector' => '#postchat_postcontent',
            'title' => '文章摘要',
            'summaryStyle' => 'https://ai.tianli0.top/static/public/postChatUser_summary.min.css',
            'postURL' => '*/archives/*',
            'blacklist' => '',
            'wordLimit' => '1000',
            'typingAnimate' => false,
            'enableSummary' => false,
            'enableAI' => false,
            'defaultInput' => false,
            'upLoadWeb' => false,
            'showInviteLink' => false,
            'backgroundColor' => '#3e86f6',
            'fill' => '#FFFFFF',
            'bottom' => '16px',
            'left' => '16px',
            'width' => '44px',
            'frameWidth' => '375px',
            'frameHeight' => '600px',
            'userTitle' => 'PostChat',
            'userDesc' => '如果你对网站的内容有任何疑问，可以来问我哦～',
            'addButton' => false,
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($settings->$key)) {
                $settings->$key = $value;
            }
        }

        $enableSummary = $settings->enableSummary ? 'true' : 'false';
        $enableAI = $settings->enableAI ? 'true' : 'false';
        $defaultInput = $settings->defaultInput ? 'true' : 'false';
        $upLoadWeb = $settings->upLoadWeb ? 'true' : 'false';
        $showInviteLink = $settings->showInviteLink ? 'true' : 'false';
        $addButton = $settings->addButton ? 'true' : 'false';

        $scriptUrl = '';
        if ($enableSummary == 'true' && $enableAI == 'true') {
            $scriptUrl = 'https://ai.tianli0.top/static/public/postChatUser_summary.min.js';
        } elseif ($enableSummary == 'true') {
            $scriptUrl = 'https://ai.tianli0.top/static/public/tianli_gpt.min.js';
        } elseif ($enableAI == 'true') {
            $scriptUrl = 'https://ai.tianli0.top/static/public/postChatUser.min.js';
        }

        if ($scriptUrl) {
            echo '<link rel="stylesheet" href="' . $settings->summaryStyle . '">';
            echo '<script>
                let tianliGPT_key = "' . $settings->key . '";
                let tianliGPT_postSelector = "' . $settings->postSelector . '";
                let tianliGPT_Title = "' . $settings->title . '";
                let tianliGPT_postURL = "' . $settings->postURL . '";
                let tianliGPT_blacklist = "' . $settings->blacklist . '";
                let tianliGPT_wordLimit = "' . $settings->wordLimit . '";
                let tianliGPT_typingAnimate = ' . ($settings->typingAnimate ? 'true' : 'false') . ';
                var postChatConfig = {
                  backgroundColor: "' . $settings->backgroundColor . '",
                  bottom: "' . $settings->bottom . '",
                  left: "' . $settings->left . '",
                  fill: "' . $settings->fill . '",
                  width: "' . $settings->width . '",
                  frameWidth: "' . $settings->frameWidth . '",
                  frameHeight: "' . $settings->frameHeight . '",
                  defaultInput: ' . $defaultInput . ',
                  upLoadWeb: ' . $upLoadWeb . ',
                  showInviteLink: ' . $showInviteLink . ',
                  userTitle: "' . $settings->userTitle . '",
                  userDesc: "' . $settings->userDesc . '",
                  addButton: ' . $addButton . '
                };
                </script>
                <script data-postChat_key="' . $settings->key . '" src="' . $scriptUrl . '"></script>';
        }
    }
}
?>
