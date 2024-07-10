<div align="center">
    <a href="https://ai.tianli0.top/" target="_blank" rel="noopener noreferrer">
        <img src="https://img.zhheo.com/i/2024/06/21/6674f00f3eb9d.webp" alt="icon"/>
    </a>
    <h1 align="center">PostChat</h1>
    <span>PostChat的Typecho插件，也支持文章摘要用户使用</span>
</div>

## 简介

![quickshot.webp](https://img.zhheo.com/i/2024/06/21/6674f0133b5b3.webp)

PostChat是一个专为中小开发者与站长开发的AI增强工具，可以在网站中插入聊天机器人和智能摘要生成的功能。本项目提供专为Typecho博客系统的插件安装包，你可以在Typecho博客中安装使用，避免了插入代码的繁琐。

## 功能

这个插件支持PostChat用户和文章摘要用户使用。文章摘要用户可以在插件设置中关闭“智能对话”功能即可。

- 文章摘要生成功能
- 文章知识库功能
- 文章知识库对话功能
- 文章AI搜索功能

更多功能可以参见：https://ai.tianli0.top/

## 本插件在Typecho中的表现

[预览地址](https://typecho.zhheo.com/archives/3/)

## PostChat在更多网站中的表现

[张洪Heo](https://blog.zhheo.com/)

[Tianli](https://tianli-blog.club/)

## 安装方式

点击右侧的[release](https://github.com/zhheo/typecho-plugin-postchat/releases)页面下载zip

解压后将`PostChat`文件夹上传到Typecho的`/usr/plugins`目录下

在Typecho后台点击顶部的`控制台`选择`插件`

![](https://img.zhheo.com/i/2024/07/10/668e198e1c22f.webp)

启用`PostChat`插件

![](https://img.zhheo.com/i/2024/07/10/668e19b9b3a70.webp)

## 插件配置

在插件设置中找到PostChat点击`设置`

![](https://img.zhheo.com/i/2024/07/10/668e19df3e7fa.webp)

进入配置界面进行配置

![](https://img.zhheo.com/i/2024/07/10/668e1a2d59c03.webp)

## 主题适配

此插件支持所有的PostChat开发API，提供主题开发者对于PostChat的控制能力。包括深色模式切换：`postChatUser.setPostChatTheme('dark')`；聊天窗口输入框：`postChatUser.setPostChatInput(content)`等。

详见开发者文档：https://postchat.zhheo.com/advanced/theme.html

## 开发者

PostChat由[张洪Heo](https://github.com/zhheo)与[Tianli](https://github.com/TIANLI0)共同构建，技术支持请联系：zhheo@qq.com（一个工作日内回复）