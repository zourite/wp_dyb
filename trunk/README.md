WordPress + DoYouBuzz = WP DYB
==============================

![Logo Plugin][1]

DoyouBuzz plugin for WordPress !

Description
===========

__FR__

[DoYouBuzz][2] est une compagnie spécialisée dans la création, la diffusion et le management de CV sur le Web.

Ce plugin vous permettera d'afficher vos experience / compétences dans une sidebar ou dans une page.

__EN__

[DoYouBuzz][3] is a company specializing in technology creation, diffusion and management of CV on the web.

This plugin allows to view your experience / skills in a sidebar or a page

Installation
===============

__FR__

1. Téléchargez, installez et activez le plugin.

2. Aller dans le Menu WP DYB et cliquez sur le lien.

3. Cliquez sur " Mettre à jour vos informations "

__EN__

1. Download, install, and activate the plugin.

2. From your Wordpress Dashboard, go to WP_DYB and click on the link.

3; Click on " Update my information"

Usage
========

__FR__

<strong>Les fonctions suivantes sont à insérer dans votre template :</strong>

* <code>wp_dyb('contact')</code> Affiche vos liens Twitter, Viadeo, Linkedin link.
* <code>wp_dyb('employment')</code> Affiche votre experience.
* <code>wp_dyb('skill')</code> Affiche vos compétences.
* <code>wp_dyb('intro')</code> Affiche la description de votre CV.
* <code>wp_dyb('formation')</code> Affiche votre formation.

<strong>Trois widgets sont disponible :</strong>

* Statut DoYouBuzz - Affiche votre disponibilité
* Compétences DoYouBuzz - Affiche vos compétences
* Experience DoYouBuzz - Affiche votre experience

__EN__

<strong>You can insert function in your template :</strong>

* <code>wp_dyb('contact')</code> Display Twitter, Viadeo, Linkedin link.
* <code>wp_dyb('employment')</code> Display your experience.
* <code>wp_dyb('skill')</code> Display your Skill.
* <code>wp_dyb('intro')</code> Display the introduction of you CV.
* <code>wp_dyb('formation')</code> Display your schooling.

<strong>Three widgets are available :</strong>

* Statut DoYouBuzz - Display if you can de employed or not.
* Compétences DoYouBuzz - Display your SKill
* Experience DoYouBuzz - Display your Experience

You can save the current document to a file using the <i class="icon-hdd"></i> `Save as...` sub-menu from the <i class="icon-provider-stackedit"></i> menu.

> **Tip:** See [<i class="icon-share"></i> Publish a document](#publish-a-document) section for a description of the different output formats.


----------


Synchronization
---------------

**StackEdit** can be combined with **Google Drive** and **Dropbox** to have your documents centralized in the *Cloud*. The synchronization mechanism will take care of uploading your modifications or downloading the latest version of your documents.

> **NOTE:**
> 
> - Full access to **Google Drive** or **Dropbox** is required to be able to import any document in StackEdit.
> - Imported documents are downloaded in your browser and are not transmitted to a server.
> - If you experience problems exporting documents to Google Drive, check and optionally disable browser extensions, such as Disconnect.

#### <i class="icon-download"></i> Import a document

You can import a document from the *Cloud* by going to the <i class="icon-provider-gdrive"></i> `Google Drive` or the <i class="icon-provider-dropbox"></i> `Dropbox` sub-menu and by clicking `Import from...`. Once imported, your document will be automatically synchronized with the **Google Drive** / **Dropbox** file.

#### <i class="icon-upload"></i> Export a document

You can export any document by going to the <i class="icon-provider-gdrive"></i> `Google Drive` or the <i class="icon-provider-dropbox"></i> `Dropbox` sub-menu and by clicking `Export to...`. Even if your document is already synchronized with **Google Drive** or **Dropbox**, you can export it to a another location. **StackEdit** can synchronize one document with multiple locations.

> **Tip:** Using **Google Drive**, you can create collaborative documents to work in real time with other users. Just check the box `Create a real time collaborative document` in the dialog options when exporting to Google Drive.

#### <i class="icon-refresh"></i> Synchronize a document

Once your document is linked to a **Google Drive** or a **Dropbox** file, **StackEdit** will periodically (every 3 minutes) synchronize it by downloading/uploading any modification. Any conflict will be detected, and a local copy of your document will be created as a backup if necessary.

If you just have modified your document and you want to force the synchronization, click the <i class="icon-refresh"></i> button in the navigation bar.

> **NOTE:** The <i class="icon-refresh"></i> button is disabled when you have no document to synchronize.

#### <i class="icon-refresh"></i> Manage document synchronization

Since one document can be synchronized with multiple locations, you can list and manage synchronized locations by clicking <i class="icon-refresh"></i> `Manage synchronization` in the <i class="icon-provider-stackedit"></i> menu. This will open a dialog box allowing you to add or remove synchronization links that are associated to your document.

> **NOTE:** If you delete the file from **Google Drive** or from **Dropbox**, the document will no longer be synchronized with that location.

----------


Publication
-----------

Once you are happy with your document, you can publish it on different websites directly from **StackEdit**. As for now, **StackEdit** can publish on **Blogger**, **Dropbox**, **Gist**, **GitHub**, **Google Drive**, **Tumblr**, **WordPress** and on any SSH server.

#### <i class="icon-share"></i> Publish a document

You can publish your document by going to the <i class="icon-share"></i> `Publish on` sub-menu and by choosing a website. In the dialog box, you can choose the publication format:

- Markdown, to publish the Markdown text on a website that can interpret it (**GitHub** for instance),
- HTML, to publish the document converted into HTML (on a blog for instance),
- Template, to have a full control of the output.

> **NOTE:** The default template is a simple webpage wrapping your document in HTML format. You can customize it in the `Services` tab of the <i class="icon-cog"></i> `Settings` dialog.

#### <i class="icon-share"></i> Update a publication

After publishing, **StackEdit** will keep your document linked to that publish location so that you can update it easily. Once you have modified your document and you want to update your publication, click on the <i class="icon-share"></i> button in the navigation bar.

> **NOTE:** The <i class="icon-share"></i> button is disabled when the document has not been published yet.

#### <i class="icon-share"></i> Manage document publication

Since one document can be published on multiple locations, you can list and manage publish locations by clicking <i class="icon-share"></i> `Manage publication` in the <i class="icon-provider-stackedit"></i> menu. This will open a dialog box allowing you to remove publication links that are associated to your document.

> **NOTE:** In some cases, if the file has been removed from the website or the blog, the document will no longer be published on that location.

----------


Markdown Extra
--------------

**StackEdit** supports **Markdown Extra**, which extends **Markdown** syntax with some nice features.

> **Tip:** You can disable any **Markdown Extra** feature in the `Extensions` tab of the <i class="icon-cog"></i> `Settings` dialog.


### Tables

**Markdown Extra** has a special syntax for tables:

Item      | Value
--------- | -----
Computer  | 1600 USD
Phone     | 12 USD
Pipe      | 1 USD

You can specify column alignment with one or two colons:

| Item      |    Value | Qty  |
| :-------- | --------:| :--: |
| Computer  | 1600 USD |  5   |
| Phone     |   12 USD |  12  |
| Pipe      |    1 USD | 234  |


### Definition Lists

**Markdown Extra** has a special syntax for definition lists too:

Term 1
Term 2
:   Definition A
:   Definition B

Term 3

:   Definition C

:   Definition D

	> part of definition D


### Fenced code blocks

GitHub's fenced code blocks[^gfm] are also supported with **Prettify** syntax highlighting:

```
// Foo
var bar = 0;
```

> **Tip:** To use **Highlight.js** instead of **Prettify**, just configure the `Markdown Extra` extension in the <i class="icon-cog"></i> `Settings` dialog.


### Footnotes

You can create footnotes like this[^footnote].

  [^footnote]: Here is the *text* of the **footnote**.


### SmartyPants

SmartyPants converts ASCII punctuation characters into "smart" typographic punctuation HTML entities. For example:

|                  | ASCII                                    | HTML                                |
 ------------------|------------------------------------------|-------------------------------------
| Single backticks | `'Isn't this fun?'`                      | &#8216;Isn&#8217;t this fun?&#8217; |
| Quotes           | `"Isn't this fun?"`                      | &#8220;Isn&#8217;t this fun?&#8221; |
| Dashes           | `-- is an en-dash and --- is an em-dash` | &#8211; is an en-dash and &#8212; is an em-dash |


### Table of contents

You can insert a table of contents using the marker `[TOC]`:

[TOC]


### Comments

Usually, comments in Markdown are just standard HTML comments. <!-- like this -->
**StackEdit** extends HTML comments in order to produce useful, highlighted comments in the preview but not in your exported documents. <!--- This is very useful for collecting feedback in a collaborative document. -->


### MathJax
 
You can render *LaTeX* mathematical expressions using **MathJax**, as on [math.stackexchange.com][4]:

The *Gamma function* satisfying $\Gamma(n) = (n-1)!\quad\forall
n\in\mathbb N$ is via the Euler integral

$$
\Gamma(z) = \int_0^\infty t^{z-1}e^{-t}dt\,.
$$

> **Tip:** Make sure you include MathJax into your publications to render mathematical expression correctly. Your page/template should include something like: 

```
<script type="text/javascript" src="https://stackedit.io/libs/MathJax/MathJax.js?config=TeX-AMS_HTML"></script>
```

> **NOTE:** You can find more information:
>
> - about **Markdown** syntax [here][5],
> - about **Markdown Extra** extension [here][6],
> - about **LaTeX** mathematical expressions [here][7],
> - about **Prettify** syntax highlighting [here][8],
> - about **Highlight.js** syntax highlighting [here][9].

  [^stackedit]: [StackEdit](https://stackedit.io/) is a full-featured, open-source Markdown editor based on PageDown, the Markdown library used by Stack Overflow and the other Stack Exchange sites.

  [^gfm]: **GitHub Flavored Markdown** (GFM) is supported by StackEdit.


  [1]: https://ps.w.org/wp-dyb/assets/banner-772x250.png
  [2]: http://www.doyoubuzz.com
  [3]: http://www.doyoubuzz.com
  [4]: http://math.stackexchange.com/
  [5]: http://daringfireball.net/projects/markdown/syntax "Markdown"
  [6]: https://github.com/jmcmanus/pagedown-extra "Pagedown Extra"
  [7]: http://meta.math.stackexchange.com/questions/5020/mathjax-basic-tutorial-and-quick-reference
  [8]: https://code.google.com/p/google-code-prettify/
  [9]: http://highlightjs.org/