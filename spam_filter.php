<?php

/*
 * spam_filter.php
 *
 * Based on akismet_filter.php version 1.2
 *  author Akio KONUMA konuma@ark-web.jp
 *  link http://www.ark-web.jp/sandbox/wiki/190.html
 *
 * @authoer SATOH Kiyoshi (satoh at hakuba dot jp)
 * @link http://miasa.info/index.php?%C8%FE%CB%E3Wiki%A4%C7%A5%B7%A5%B9%A5%C6%A5%E0%C5%AA%A4%CB%BD%A4%C0%B5%A4%B7%A4%C6%A4%A4%A4%EB%C5%C0
 * @version 0.7.6
 * @license GPL v2 or (at your option) any later version
 */

//// pukiwiki.ini.php�ʤɤǳƥ��ѥ�ե��륿�����Ѥȥե��륿��λ���򤹤�
// �������Ƥ�̿̾��§������
// SPAM_FILTER_****_PLUGIN_NAME -> �����å��оݤȤ���ץ饰����̾������޶��ڤ�
// SPAM_FILTER_****_REG         -> �ޥå�����������ɽ��
// SPAM_FILTER_****_URLREG      -> URL���̤��뤿�������ɽ��
// SPAM_FILTER_****_WHITEREG    -> �ޥå����ʤ��Ƥ褤URL�ۥ磻�ȥꥹ��

//// ���ѥ��Ƚ�Ǥ��������ꤹ��
// ���ꤵ�줿�Ƽ凉�ѥ�ե��륿�������̤ä���FALSE
// �ڤ��ե��륿�����˳ݤ���false positive�β�ǽ���������Τ�ʣ����ǳݤ���
// ��SPAM_FILTER_COND ������
// ��UserAgent��libwww����HTML��ź�եե����롢</a>�����������롢�Ѹ�Τߤ�URL��3�İʾ塢URL��NS�Υ֥�å��ꥹ��
//define('SPAM_FILTER_COND', '#useragent() or #filename() or #atag() or (#onlyeng() and #urlnum()) or #urlnsbl()');
// ���嵭���˥ץ饹���Ѹ�ΤߤΤȤ���URL������Ȥ�����Akismet������
//define('SPAM_FILTER_COND', '#useragent() or #filename() or #atag() or (#onlyeng() and #urlnum()) or #urlnsbl() or (#onlyeng() and #url() and #akismet())');
// ���ǥե���ȤǤϥե��륿�ʤ�
define('SPAM_FILTER_COND', '');

//// CAPTCHA�ǤΥ����å��򤹤������ꤹ��
// ���ǥե���ȤǤϥե��륿�ʤ�
define('SPAM_FILTER_CAPTCHA_COND', '');

//// �ƥե��륿���̤�����Ǥ������
// URL�ǤΥޥå��Ǽ��ɥᥤ��ʤɤ�̵�뤹�٤�URL
define('SPAM_FILTER_WHITEREG', '/example\.(com|net|jp)/i');
// URL����Ф���ݤ�����ɽ��
define('SPAM_FILTER_URLREG', '/(?:(?:https?|ftp|news):\/\/)[\w\/\@\$()!?&%#:;.,~\'=*+-]+/i');

//// urlnsbl �ʤɤǻȤ���NS�μ����򤹤� dns_get_ns ������
// NS���������̤򤢤����٥���å��夷�Ƥ���
define('SPAM_FILTER_DNSGETNS_CACHE_FILE', 'dns_get_ns.cache');
// ����å��夷�Ƥ�������
define('SPAM_FILTER_DNSGETNS_CACHE_DAY', 30);
// nslookup ���ޥ�ɤؤΥѥ� - PHP4�ξ��ʤɤ�ɬ�פȤʤ��礬����
define('SPAM_FILTER_NSLOOKUP_PATH', '/usr/bin/nslookup');

//// ipcountry �ʤɤǻȤ���IP����񥳡��ɤ�������� get_country_code ������
// IP���ɥ쥹�Ӥȹ����ν񤫤줿�ե�����̾
define('SPAM_FILTER_IPCOUNTRY_FILE', 'delegated-apnic-latest');

//// ngreg     - ���Ƥ�����ɽ���ե��륿
// ��������ǵ��Ĥ��ʤ����Ƥ�����ɽ��
define('SPAM_FILTER_NGREG_REG', '');
define('SPAM_FILTER_NGREG_PLUGIN_NAME', 'edit,comment,pcomment,article');

//// url       - ���Ƥ�URL�äݤ���Τ��ޤޤ�Ƥ��뤫�����å�
define('SPAM_FILTER_URL_REG', '/https?:/i');
define('SPAM_FILTER_URL_PLUGIN_NAME', 'edit,comment,pcomment,article');

//// atag      - ���Ƥ�</A>��[/URL]�Τ褦�ʥ��󥫡��������ޤޤ�Ƥ��뤫�����å�
define('SPAM_FILTER_ATAG_REG', '/<\/a>|\[\/url\]/i');
define('SPAM_FILTER_ATAG_PLUGIN_NAME', 'edit,comment,pcomment,article');

//// onlyeng   - ���Ƥ�Ⱦ�ѱѿ��Τ�(���ܸ줬���äƤ��ʤ�)�������å�
define('SPAM_FILTER_ONLYENG_REG', '/\A[!-~\n ]+\Z/');
define('SPAM_FILTER_ONLYENG_PLUGIN_NAME', 'edit,comment,pcomment,article');

//// urlnum    - ���Ƥ˴ޤޤ�Ƥ���URL�����İʾ夫�����å�
define('SPAM_FILTER_URLNUM_NUM', '3');
define('SPAM_FILTER_URLNUM_WHITEREG', SPAM_FILTER_WHITEREG);
define('SPAM_FILTER_URLNUM_URLREG', SPAM_FILTER_URLREG);
define('SPAM_FILTER_URLNUM_PLUGIN_NAME', 'edit,comment,pcomment,article');

//// ipunknown - ���饤����Ȥ�IP���հ����Ǥ��뤫�����å�
define('SPAM_FILTER_IPUNKNOWN_PLUGIN_NAME', 'edit,comment,pcomment,article,attach');

//// ips25r    - ���饤����Ȥ�IP��ưŪIP�äݤ�(S25R�˥ޥå�����)�������å�
// S25R������ɽ��
define('SPAM_FILTER_IPS25R_REG', '/(^[^\.]*[0-9][^0-9\.]+[0-9])|(^[^\.]*[0-9]{5})|(^([^\.]+\.)?[0-9][^\.]*\.[^\.]+\..+\.[a-z])|(^[^\.]*[0-9]\.[^\.]*[0-9]-[0-9])|(^[^\.]*[0-9]\.[^\.]*[0-9]\.[^\.]+\..+\.)|(^(dhcp|dialup|ppp|adsl)[^\.]*[0-9])|\.(internetdsl|adsl|sdi)\.tpnet\.pl$/');
define('SPAM_FILTER_IPS25R_PLUGIN_NAME', 'tb');

//// ipbl      - ���饤����Ȥ�IP��ۥ���̾�ˤ��ե��륿
// ���Ĥ��ʤ�IP��ۥ���̾������ɽ��
define('SPAM_FILTER_IPBL_REG', '');
define('SPAM_FILTER_IPBL_PLUGIN_NAME', 'edit,comment,pcomment,article,attach');
// �ۥ���̾�����Ĥ����ʤ��ä��Ȥ��ˤ���ݤ����� TRUE
define('SPAM_FILTER_IPBL_UNKNOWN', FALSE);

//// ipdnsbl   - ���饤����Ȥ�IP��DNSBL�ǥ����å�
define('SPAM_FILTER_IPDNSBL_DNS', 'dnsbl.spam-champuru.livedoor.com,niku.2ch.net,bsb.spamlookup.net,bl.spamcop.net,all.rbl.jp');
define('SPAM_FILTER_IPDNSBL_PLUGIN_NAME', 'edit,comment,pcomment,article,attach');

//// ipcountry - ���饤����Ȥ�IP�ι������å�
// �ޥå�����������ꤹ������ɽ��
define('SPAM_FILTER_IPCOUNTRY_REG', '/(CN|KR|UA)/');
define('SPAM_FILTER_IPCOUNTRY_PLUGIN_NAME', 'edit,comment,pcomment,article,attach');

//// uaunknown - HTTP_USER_AGENT������(pukiwiki.ini.php��$agents�ǻ���)�������å�
define('SPAM_FILTER_UAUNKNOWN_PLUGIN_NAME', 'edit,comment,pcomment,article,attach');

//// useragent - HTTP_USER_AGENT�ˤ��ե��륿
// ���Ĥ��ʤ�HTTP_USER_AGENT������ɽ��
define('SPAM_FILTER_USERAGENT_REG', '/WWW-Mechanize|libwww/i');
define('SPAM_FILTER_USERAGENT_PLUGIN_NAME', 'edit,comment,pcomment,article,attach');

//// acceptlanguage - HTTP_ACCEPT_LANGUAGE�ˤ��ե��륿
// ���Ĥ��ʤ�HTTP_ACCEPT_LANGUAGE������ɽ��
define('SPAM_FILTER_ACCEPTLANGUAGE_REG', '/cn/i');
define('SPAM_FILTER_ACCEPTLANGUAGE_PLUGIN_NAME', 'edit,comment,pcomment,article,attach');

//// filename  - ���åץ��ɥե�����̾�ˤ��ե��륿
// ���åץ��ɤ���Ĥ��ʤ��ե�����̾������ɽ��
define('SPAM_FILTER_FILENAME_REG', '/\.html$|\.htm$/i');
define('SPAM_FILTER_FILENAME_PLUGIN_NAME', 'attach');

//// formname  - ¸�ߤ��ʤ��Ϥ��Υե��������Ƥ����뤫�����å�
// ¸�ߤ��ʤ��Ϥ��Υե�����̾�λ��ꡢ����޶��ڤ�
define('SPAM_FILTER_FORMNAME_NAME', 'url,email');
define('SPAM_FILTER_FORMNAME_PLUGIN_NAME', 'edit,comment,pcomment,article');

//// urlbl     - URL���֥�å��ꥹ�Ȥ����äƤ��뤫��ǧ
// URL�Υ֥�å��ꥹ�� �ۥ���̾�Ǥ�IP�Ǥ��
// ��wikiwiki.jp�Υ֥�å��ꥹ�Ȥ򻲹�
// ��http://wikiwiki.jp/?%A5%D5%A5%A3%A5%EB%A5%BF%A5%EA%A5%F3%A5%B0%A5%C9%A5%E1%A5%A4%A5%F3%B5%DA%A4%D3%A5%A2%A5%C9%A5%EC%A5%B9
define('SPAM_FILTER_URLBL_REG', '/(0451\.net|1\.sa3\.cn|1102213\.com|1234\.hao88cook\.com|1234564898\.h162\.1stxy\.cn|123lineage\.com|136136\.net|16isp\.com|17aa\.com|17tc\.com|18dmm\.com|18dmm\.com|18girl-av\.com|19800602\.com|1boo\.net|1gangmu\.com|1stxy\.cn|1stxy\.net|216\.168\.128\.126|2chjp\.com|453787\.com|500bb\.com|53dns\.com|56jb\.com|59\.36\.96\.140|5xuan\.com|60\.169\.0\.66|60\.171\.45\.134|66\.98\.212\.108|666\.lyzh\.com|6789\.hao88cook\.com|77276\.com|78xian\.com|84878679\.free\.psnic\.cn|853520\.com|8ycn\.com|92\.av366\.com|a\.2007ip\.com|a\.xiazaizhan\.cn|aaa-livedoor\.net|acyberhome\.com|adfka\.com|adult\.zu1\.ru|ahatena\.com|ahwlqy\.com|anemony\.info|angel\.hao88cook\.com|anyboard\.net|areaseo\.com|asdsdgh-jp\.com|askbigtits\.com|aspasp\.h162\.1stxy\.cn|aurasoul-visjp\.com|auto-mouse\.com|auto-mouse\.jp|avl\.lu|avtw1068\.com|baidu\.chinacainiao\.org|baidulink\.com|bailishidai\.com|bbs-qrcode\.com|bbs\.coocbbs\.com|bestinop\.org|beyondgame\.jsphome\.com|bibi520\.com|bibi520\.h20\.1stxy\.cn|bizcn\.com|blog-livedoor\.net|blogplaync\.com|bluell\.cn|blusystem\.com|bosja\.com|cash\.searchbot\.php|cashette\.com|casino\.online|cc\.wzxqy\.com|cetname\.com|cgimembera\.org|cglc\.org|chengzhibing\.com|china-beijing-cpa\.com|chinacainiao\.org|chinacu\.net|chnvip\.net|chouxiaoya\.org|city689\.com|cityhokkai\.com|cn7135\.cn|cnidc\.cn|conecojp\.net|coocbbs\.com|cool\.47555\.com|coolroge\.199\.53dns\.com|cpanel\.php|cyd\.org\.uk|d\.77276\.com|dcun\.cn|dfsm\.jino-net\.ru|dietnavi\.com|din-or\.com|dj5566\.org|djkkk66990\.com|dl\.gov\.cn|do\.77276\.com|down\.136136\.net|down\.eastrun\.net|down123\.net|dtg-gamania\.com|ee28\.cn|efnm\.w170\.bizcn\.com|emarealtor\.com|ff11-info\.com|ffxiforums\.net|fhy\.net|filthyloaded\.com|fizkult\.org|fly\.leryi\.com|fofje\.info|forumup\.us|forumup\.us|ftplin\.com|fxfqiao\.com|gamaniaech\.com|game-click\.com|game-fc2blog\.com|game-mmobbs\.com|game-oekakibbs\.com|game\.16isp\.com|game4enjoy\.net|game62chjp\.net|gamecent\.com|gameloto\.com|games-nifty\.com|gameslin\.net|gamesragnaroklink\.net|gamesroro\.com|gamet1\.com|gameurdr\.com|gameyoou\.com|gamshondamain\.net|ganecity\.com|gangnu\.com|gemnnammobbs\.com|gendama\.jp|geocitygame\.com|geocitylinks\.com|getamped-garm\.com|ggmm52\.com|ghostsoft\.info|girl-o\.com|gogogoo\.com|good1688\.com|goodclup\.com|google\.cn\.mmhk\.cn|grandchasse\.com|gsisdokf\.net|guoxuecn\.com|gwlz\.cn|hao88cook\.com|hao88cook\.xinwen365\.net|haveip\.com|heixiou\.com|hinokihome\.com\.tw|homepage3-nifty\.com|honda168\.net|hosetaibei\.com|hoyoo\.net|hyap98\.com|i5460\.net|i5460\.net|ic-huanao\.com|iframedollars\.biz|ii688\.com|itgozone\.com|ixbt\.com|izmena\.org|j4sb\.com|japan\.k15\.cn|japan213\.com|japangame1\.com|jdnx\.movie721\.cn|jinluandian\.com|joyjc\.com|joynu\.com|jp\.hao88cook\.com|jpgame666\.com|jpgamer\.net|jpgamermt\.com|jplin\.com|jplineage\.com|jplingood\.com|jplinux\.com|jplove888\.com|jpplay\.net|jpragnarokonline\.com|jprmthome\.com|js1988\.com|jsphome\.com|jswork\.jp|jtunes\.com|jtunes\.com|junkmetal\.info|junkmetal\.info|k15\.cn|kaihatu\.com|kanikuli\.net|kaukoo\.com|kele88\.com|kiev\.ua|kingbaba\.cc|kingrou\.w177\.west263\.cn|kingshi\.net|kingtt\.com|kmqe\.com|kortwpk\.com|korunowish\.com|kotonohax\.com|kulike\.com|kuronowish\.net|kyoukk\.com|la-ringtones\.com|lastlineage\.com|lele\.0451\.net|lin2-jp\.com|linainfo\.net|linbbs\.com|lindeliang-36248700\.15\.cnidc\.cn|lineagalink\.com|lineage-info\.com|lineage\.1102213\.com|lineage\.japan213\.com|lineage1bbs\.com|lineage2-ol\.com|lineage2\.japan213\.com|lineage2006\.com|lineage321\.com|lineagecojp\.com|lineagefirst\.com|lineageink\.com|lineagejp-game\.com|lineagejp-game\.com|lineagejp\.com|lineagekin\.com|lineagett\.com|lineinfo-jp\.com|linenew\.com|lingage\.com|lingamesjp\.com|linjp\.net|linkcetou\.com|linrmb\.com|linsssgame\.com|livedoor1\.com|lliinnss\.com|lovejpjp\.com|lovejptt\.com|lovetw\.webnow\.biz|lyadsl\.com|lyftp\.com|lyzh\.com|macauca\.org\.mo|mail\.8u8y\.com|maplestorfy\.com|micro36\.com|mm\.7mao\.com|mmhk\.cn|mogui\.k15\.cn|moguidage\.h81\.1stxy\.net|mojeforum\.net|monforum\.com|movie1945\.com|mumu\.8ycn\.com|nakosi\.com|navseh\.com|netgamelivedoor\.com|nobunaga\.1102213\.com|nothing-wiki\.com|okinawa\.usmc-mccs\.org|okwit\.com|omakase-net\.com|oulianyong\.com|pagead2\.googlesyndication\.com\.mmhk\.cn|pangzigame\.com|phpnet\.us|planetalanismorissette\.info|playerturbo\.com|playncsoft\.net|playsese\.com|plusintedia\.com|pointlink\.jp|potohihi\.com|ptxk\.com|puma163\.com|qbbd\.com|qianwanip\.cn|qiucong\.com|qq\.ee28\.cn|qq756\.com|quicktopic\.com|rabota\.inetbiznesman\.ru|ragnarok-bbs\.com|ragnarok-game\.com|ragnarok-sara\.com|ragnaroklink\.com|ragnarokonlina\.com|ragnarokonline1\.com|ragnarox\.mobi|rarbrc\.com|rb\.17aa\.com|rbtt1\.com|realitsen\.info|rik\.tag-host\.com|riro\.bibi520\.com|rit1\.bibi520\.com|rit2\.bibi520\.com|rmt-lineagecanopus\.com|rmt-navip\.com|rmt-ranloki\.com|rmt-trade\.com|ro-bot\.net|rogamesline\.com|rokonline-jp\.com|rootg\.org|roprice\.com|rormb\.com|s57\.cn|s678\.cn|scandius\.com|sepgon\.com|setsoul\.org|seun\.ru|seun\.ru|sf\.sf325\.com|shakiranudeworld\.info|shoopivdoor\.com|shoopivdoor\.w19\.cdnhost\.cn|skkustp\.itgozone\.com|skoro\.us|skybeisha\.com|slower-qth\.com|slower-qth\.com|stats\.dl\.gov\.cn|suniuqing\.com|suzukl668\.com|taiwanioke\.com|tankhaoz\.com|tbihome\.org|tesekl\.kmip\.net|thewildrose\.net|thtml\.com|tigermain\.w148\.bizcn\.com|tooplogui\.com|toyshop\.com\.tw|trade-land\.net|trans2424\.com|ttbbss123\.com|tulang1\.com|twabout\.com|twb1og\.net|twganwwko\.com|twguoyong\.com|twmsn-ga\.com|twsunkom\.com|twtaipei\.org|ubtop\.com|usmc-mccs\.org|vegas-webspace\.com|w666\.cn|watcheimpress\.com|watchsite\.nm\.ru|web\.77276\.com|webnow\.biz|wenyuan\.com\.cn|west263\.cn|wikiwiKi-game\.com|woowoo\.com\.cn|wowsquare\.com|wulgame\.com|www2\.cw988\.cn|xiaoshuowang\.com\.cn|xintao-01\.woowoo\.com\.cn|xinwen365\.net|xpills\.info|xulao\.com|xx\.wzxqy\.com|xx20062\.kele88\.com|xxlin\.com|xz\.llliao\.com|xzqx88\.com|yahoo-gamebbs\.com|yahoo\.chinacainiao\.org|yangjicook\.com|yingzhiyuan\.com|yohoojp\.com|youshini\.com|youtnwaht\.tw\.cn|youxigg\.com|yujinmp\.com|ywdgigkb-jp\.com|yzlin\.com|zaprosov\.com|zhangweijp\.com|zhangweijp\.w100\.okwit\.com|zhangwenbin-tian1\.14\.cnidc\.cn|zixinzhu\.cn|zn360\.com|zoo-sex\.com\.ua|ok8vs\.com|blog-ekndesign\.com|gamesmusic-realcgi\.net|homepage-nifty\.com|jpxpie6-7net\.com|irisdti-jp\.com|plusd-itmedia\.com|runbal-fc2web\.com|jklomo-jp\.com|d-jamesinfo\.com|deco030-cscblog\.com|ie6xp\.com|gomeodc\.com|vviccd520\.com|ipqwe\.com|mumy8\.com|okvs8\.com|p5ip\.com|plmq\.com|y8ne\.com|yyc8\.com|cityblog-fc2web\.com|extd-web\.com|gamegohi\.com|a-hatena\.com|ragnarok-search\.com|23styles\.com|ezbbsy\.com|livedoor-game\.com|m-phage\.com|yy14-kakiko\.com|lian-game\.com|ezbbs\.com|dentsu\.itgo\.com)/i');
define('SPAM_FILTER_URLBL_WHITEREG', SPAM_FILTER_WHITEREG);
define('SPAM_FILTER_URLBL_URLREG', SPAM_FILTER_URLREG);
define('SPAM_FILTER_URLBL_PLUGIN_NAME', 'edit,comment,pcomment,article');
// IP�����Ĥ����ʤ��ä��Ȥ��ˤ���ݤ����� TRUE
define('SPAM_FILTER_URLBL_UNKNOWN', FALSE);

//// urlcountry  - URL�Υ����ФΤ���������å�
// �ޥå�����������ꤹ������ɽ��
define('SPAM_FILTER_URLCOUNTRY_REG', '/(CN|KR|UA)/');
define('SPAM_FILTER_URLCOUNTRY_WHITEREG', SPAM_FILTER_WHITEREG);
define('SPAM_FILTER_URLCOUNTRY_URLREG', SPAM_FILTER_URLREG);
define('SPAM_FILTER_URLCOUNTRY_PLUGIN_NAME', 'edit,comment,pcomment,article');

//// urldnsbl  - URL��DNSBL�����äƤ��뤫��ǧ
// DNSBL�Υꥹ��
define('SPAM_FILTER_URLDNSBL_DNS', 'url.rbl.jp,rbl.bulkfeeds.jp,multi.surbl.org,list.uribl.com,bsb.spamlookup.net');
define('SPAM_FILTER_URLDNSBL_WHITEREG', SPAM_FILTER_WHITEREG);
define('SPAM_FILTER_URLDNSBL_URLREG', SPAM_FILTER_URLREG);
define('SPAM_FILTER_URLDNSBL_PLUGIN_NAME', 'edit,comment,pcomment,article');

//// urlnsbl   - URL��NS���֥�å��ꥹ�Ȥ����äƤ��뤫��ǧ
// URL��NS�Υ֥�å��ꥹ�� �ۥ���̾�Ǥ�IP�Ǥ��
// ��wikiwiki.jp�Υ֥�å��ꥹ�Ȥ򻲹�
// ��http://wikiwiki.jp/?%A5%D5%A5%A3%A5%EB%A5%BF%A5%EA%A5%F3%A5%B0%A5%C9%A5%E1%A5%A4%A5%F3%B5%DA%A4%D3%A5%A2%A5%C9%A5%EC%A5%B9
define('SPAM_FILTER_URLNSBL_REG', '/(\.dnsfamily\.com|\.xinnet\.cn|\.xinnetdns\.com|\.bigwww\.com|\.4everdns\.com|\.myhostadmin\.net|\.dns\.com\.cn|\.hichina\.com|\.cnmsn\.net|\.focusdns\.com|\.cdncenter\.com|\.cnkuai\.cn|\.cnkuai\.com|\.cnolnic\.com|\.dnspod\.net|\.mywebserv\.com|216\.195\.58\.5[0-9])/i');
define('SPAM_FILTER_URLNSBL_WHITEREG', SPAM_FILTER_WHITEREG);
define('SPAM_FILTER_URLNSBL_URLREG', SPAM_FILTER_URLREG);
define('SPAM_FILTER_URLNSBL_PLUGIN_NAME', 'edit,comment,pcomment,article');
// NS�����Ĥ����ʤ��ä��Ȥ��ˤ���ݤ����� TRUE
define('SPAM_FILTER_URLNSBL_NSUNKNOWN', FALSE);

//// urlnscountry - URL��NS�ι������å�
// �ޥå�����������ꤹ������ɽ��
define('SPAM_FILTER_URLNSCOUNTRY_REG', '/(CN|KR|UA)/');
define('SPAM_FILTER_URLNSCOUNTRY_WHITEREG', SPAM_FILTER_WHITEREG);
define('SPAM_FILTER_URLNSCOUNTRY_URLREG', SPAM_FILTER_URLREG);
define('SPAM_FILTER_URLNSCOUNTRY_PLUGIN_NAME', 'edit,comment,pcomment,article');
// NS�����Ĥ����ʤ��ä��Ȥ��ˤ���ݤ����� TRUE
define('SPAM_FILTER_URLNSCOUNTRY_NSUNKNOWN', FALSE);

//// akismet   - Akismet �ˤ��ե��륿
// ���ѥ�����å����ˤ�̵�뤹��Post�ǡ���������޶��ڤ�
define('SPAM_FILTER_AKISMET_IGNORE_KEY', 'digest');
// Akismet�Ǽ������롣API����
define('SPAM_FILTER_AKISMET_API_KEY', '');
define('SPAM_FILTER_AKISMET_PLUGIN_NAME', 'edit,comment,tracker,article');

//// reCAPTCHA ������
define('SPAM_FILTER_RECAPTCHA_PUBLICKEY', '');
define('SPAM_FILTER_RECAPTCHA_PRIVATEKEY', '');


define('SPAM_FILTER_IS_WINDOWS', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));

//// ���ѥ�ե��륿����
// plugin.php ����ƤФ��
function spam_filter($plugin)
{
    $spamfilter = new SpamFilter($_POST, $plugin);

    // CAPTCHA�Ǥ�ǧ�ڤ��̤äƤ����礽�Τޤ��̤�
    if ($spamfilter->captcha_check()) return;

    // ���ݾ��˹�äƤ����硢CAPTCHA�Ǥ�ǧ�ڤ��餻����λ
    if ($spamfilter->is_spam())
        die_message( "Spam check failed. Plugin:". $spamfilter->plugin_name ." Match:". $spamfilter->message ."<br>\n" );

    // CAPTCHA������˹�äƤ����硢CAPTCHA��ɽ��
    if ($spamfilter->is_spam(SPAM_FILTER_CAPTCHA_COND))
        $spamfilter->captcha_show();
}


//// ���ѥ�ե��륿���饹
// �ե��륿�Ѥ�û���ؿ�̾��̾�����֤�����ʤ����᥯�饹�ǤޤȤ᤿���
class SpamFilter
{
    // �ƥ��ѥ�ե��륿�ǻ��Ȥ���ǡ���
    var $post_data;   // ��Ƥ��줿����
    var $plugin_name; // �ƤӽФ��줿�ץ饰����̾
    var $message;     // ���顼�����Ѥ˥ޥå��������ʤɤ��ɵ����Ƥ���
    var $dns_get_ns_cache; // dns_get_ns�Υ���å�����

    function SpamFilter($post, $plugin)
    {
        $this->post_data = $post;
        $this->plugin_name = $plugin;
        $this->message = '';
    }

    // SPAM_FILTER_COND �ǻ��ꤵ�줿���ѥ�ե��륿��ݤ���
    function is_spam($cond = SPAM_FILTER_COND)
    {
        // edit �� preview �ΤȤ��ϥ����å��ݤ��ʤ�
        global $vars;
        if ($this->plugin_name == 'edit' && isset($vars['preview'])) return FALSE;
        // �ե��륿���λ��꤬�ʤ���Ф��Τޤ��֤�
        if (preg_match('/^\s*$/', $cond)) return FALSE;

        // �ޥå���������񤭽Ф��Хåե��򥯥ꥢ
        $this->message = '';
        // �ե��륿�����������Ƥ�������å��ݤ���
        $cond = preg_replace('/#/', '$this->', $cond);
        $cond = 'return('. $cond .');';
        return eval( $cond );
    }

    function check_plugin($pluginnames)
    {
        $plugin_names = explode(",", $pluginnames);
        return in_array($this->plugin_name, $plugin_names);
    }

    // ���Ƥ�����ɽ�������å�
    function ngreg($reg = SPAM_FILTER_NGREG_REG,
                   $pluginnames = SPAM_FILTER_NGREG_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        if (preg_match($reg, $this->post_data['msg'])) {
            $this->message .= 'ngreg ';
            return TRUE;
        }

        return FALSE;
    }

    // ���ƤΤ�URL���ޤޤ�Ƥ��뤫�����å�
    function url($reg = SPAM_FILTER_URL_REG,
                 $pluginnames = SPAM_FILTER_URL_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        if (preg_match($reg, $this->post_data['msg'])) {
            $this->message .= 'url ';
            return TRUE;
        }

        return FALSE;
    }

    // ���Ƥ�</A>��[/URL]�Τ褦�ʥ��󥫡��������ޤޤ�Ƥ��뤫�����å�
    function atag($reg = SPAM_FILTER_ATAG_REG,
                  $pluginnames = SPAM_FILTER_ATAG_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        if (preg_match($reg, $this->post_data['msg'])) {
            $this->message .= 'atag ';
            return TRUE;
        }

        return FALSE;
    }

    // ���Ƥ�Ⱦ�ѱѿ��Τ�(���ܸ줬���äƤ��ʤ�)�������å�
    function onlyeng($reg = SPAM_FILTER_ONLYENG_REG,
                     $pluginnames = SPAM_FILTER_ONLYENG_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        if (preg_match($reg, $this->post_data['msg'])) {
            $this->message .= 'onlyeng ';
            return TRUE;
        }

        return FALSE;
    }

    // ���Ƥ˴ޤޤ�Ƥ���URL�����İʾ夫�����å�
    function urlnum($num = SPAM_FILTER_URLNUM_NUM,
                    $whitereg = SPAM_FILTER_URLNUM_WHITEREG,
                    $urlreg = SPAM_FILTER_URLNUM_URLREG,
                    $pluginnames = SPAM_FILTER_URLNUM_PLUGIN_NAME)
    {
        //        die_message("in urlnum plugin_name". $this->plugin_name);
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // �������URL�����
        preg_match_all($urlreg, $this->post_data['msg'], $urls);
        foreach ($urls[0] as $url) {
            // �ۥ���̾���ۥ磻�ȥꥹ�Ȥˤ������̵�뤷�Ƽ���URL�Υ����å���
            if (preg_match($whitereg, $url)) continue;

            // �ۥ磻�ȥꥹ�Ȥ˥ޥå����ʤ��ä��Ȥ��ϥ�����ȥ��å�
            $link_count ++;
        }
        if ($link_count >= $num) {
            $this->message .= 'urlnum ';
            return TRUE;
        }

        return FALSE;
    }

    // ���饤����Ȥ�IP���հ����Ǥ��뤫�����å�
    function ipunknown($pluginnames = SPAM_FILTER_IPUNKNOWN_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // IP�����ꤵ��Ƥ��ʤ�����Ĵ�٤��ʤ��Τ��̤�
        if (empty($_SERVER['REMOTE_ADDR'])) return FALSE;

        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if (empty($hostname)) {
            $this->message .= 'ipunknown ';
            return TRUE;
        }

        return FALSE;
    }

    // ���饤����Ȥ�IP��ưŪIP�äݤ�(S25R�˥ޥå�����)�������å�
    function ips25r($reg = SPAM_FILTER_IPS25R_REG,
                    $pluginnames = SPAM_FILTER_IPS25R_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // IP�����ꤵ��Ƥ��ʤ�����Ĵ�٤��ʤ��Τ��̤�
        if (empty($_SERVER['REMOTE_ADDR'])) return FALSE;

        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if (empty($hostname) || preg_match($reg, $hostname)) {
            $this->message .= 'ips25r ';
            return TRUE;
        }

        return FALSE;
    }

    // ���饤����Ȥ�IP�Υ����å�
    function ipbl($reg = SPAM_FILTER_IPBL_REG,
                  $pluginnames = SPAM_FILTER_IPBL_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // IP�����ꤵ��Ƥ��ʤ�����Ĵ�٤��ʤ��Τ��̤�
        if (empty($_SERVER['REMOTE_ADDR'])) return FALSE;

        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if (preg_match($reg, $_SERVER['REMOTE_ADDR']) ||
            preg_match($reg, $hostname)) {
            $this->message .= 'ipbl ';
            return TRUE;
        }
        if (SPAM_FILTER_IPBL_UNKNOWN && empty($hostname)) {
            $this->message .= 'ipbl(unknown) ';
            return TRUE;
        }

        return FALSE;
    }

    // ���饤����Ȥ�IP��DNSBL�ǥ����å�
    function ipdnsbl($dnss = SPAM_FILTER_IPDNSBL_DNS,
                     $pluginnames = SPAM_FILTER_IPDNSBL_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // IP�����ꤵ��Ƥ��ʤ�����Ĵ�٤��ʤ��Τ��̤�
        if (empty($_SERVER['REMOTE_ADDR'])) return FALSE;

        $dns_hosts = explode(",", $dnss);
        $ip = $_SERVER['REMOTE_ADDR'];
        $revip = implode('.', array_reverse(explode('.', $ip)));

        foreach ($dns_hosts as $dns) {
            $lookup = $revip . '.' . $dns;
            $result = gethostbyname($lookup);
            if ($result != $lookup) {
                $this->message .= 'ipdnsbl ';
                return TRUE;
            }
        }

        return FALSE;
    }

    // ���饤����Ȥ�IP�ι������å�
    function ipcountry($reg = SPAM_FILTER_IPCOUNTRY_REG,
                       $pluginnames = SPAM_FILTER_IPCOUNTRY_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // IP�����ꤵ��Ƥ��ʤ�����Ĵ�٤��ʤ��Τ��̤�
        if (empty($_SERVER['REMOTE_ADDR'])) return FALSE;

        $country = $this->get_country_code( $_SERVER['REMOTE_ADDR'] );
        if (preg_match($reg, $country)) {
            $this->message .= 'ipcountry ';
            return TRUE;
        }

        return FALSE;
    }

    // HTTP_USER_AGENT������(pukiwiki.ini.php��$agents�ǻ���)�������å�
    function uaunknown($pluginnames = SPAM_FILTER_UAUNKNOWN_PLUGIN_NAME)
    {
        global $agents;

        if (!$this->check_plugin($pluginnames)) return FALSE;

        // UserAgent�ͤ����ꤵ��Ƥ��ʤ����ϵ���
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            $this->message .= 'uaunknown(empty) ';
            return TRUE;
        }

        // $agents�κǸ�ˤ���default���ʳ��ȥޥå�������
        $agents_temp = $agents;
        array_pop( $agents_temp );
        foreach ($agents_temp as $agent) {
            // �ɤ줫��UA�ȥޥå�����������ʤ�
            if (preg_match($agent['pattern'], $_SERVER['HTTP_USER_AGENT'])) return FALSE;
        }
        // �ɤ�UA�Ȥ�ޥå����ʤ��ä�
        $this->message .= 'uaunknown ';
        return TRUE;
    }

    // HTTP_USER_AGENT�Υ����å�
    // �����Ѥˤ� HTTP_USER_AGENT ��ä��ʤ��褦 init.php �إѥå���ɬ�פ���
    function useragent($reg = SPAM_FILTER_USERAGENT_REG,
                       $pluginnames = SPAM_FILTER_USERAGENT_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // UserAgent�ͤ����ꤵ��Ƥ��ʤ����ϵ���
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            $this->message .= 'uaunknown(empty) ';
            return TRUE;
        }

        if (preg_match($reg, $_SERVER['HTTP_USER_AGENT'])) {
            $this->message .= 'useragent ';
            return TRUE;
        }

        return FALSE;
    }

    // HTTP_ACCEPT_LANGUAGE�Υ����å�
    function acceptlanguage($reg = SPAM_FILTER_ACCEPTLANGUAGE_REG,
                            $pluginnames = SPAM_FILTER_ACCEPTLANGUAGE_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // AcceptLanguage�ͤ����ꤵ��Ƥ��ʤ����ϵ���
        if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->message .= 'alunknown(empty) ';
            return TRUE;
        }

        if (preg_match($reg, $_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->message .= 'acceptlanguage ';
            return TRUE;
        }

        return FALSE;
    }

    // ���åץ��ɥե�����̾�ˤ��ե��륿
    function filename($reg = SPAM_FILTER_FILENAME_REG,
                      $pluginnames = SPAM_FILTER_FILENAME_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        if (isset($_FILES['attach_file'])) {
            $file = $_FILES['attach_file'];
            if (preg_match($reg, $file['name'])) {
                $this->message .= 'filename ';
                return TRUE;
            }
        }

        return FALSE;
    }

    // ¸�ߤ��ʤ��Ϥ��Υե��������Ƥ����뤫�����å�
    function formname($formnames = SPAM_FILTER_FORMNAME_NAME,
                      $pluginnames = SPAM_FILTER_FORMNAME_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // ���ꤵ�줿̾���Υե���������Ƥ��ʤˤ����뤫��ǧ
        $form_names = explode(",", $formnames);
        foreach ($form_names as $name) {
            if (!empty($this->post_data["$name"])) {
                $this->message .= 'formname ';
                return TRUE;
            }
        }

        return FALSE;
    }

    // URL���֥�å��ꥹ�Ȥ����äƤ��뤫��ǧ
    function urlbl($reg = SPAM_FILTER_URLBL_REG,
                   $whitereg = SPAM_FILTER_URLBL_WHITEREG,
                   $urlreg = SPAM_FILTER_URLBL_URLREG,
                   $pluginnames = SPAM_FILTER_URLBL_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // �������URL�����
        preg_match_all($urlreg, $this->post_data['msg'], $urls);
        foreach ($urls[0] as $url) {
            // URL�Υۥ���̾����ɥᥤ�������
            $url_array = parse_url($url);
            $hostname = $url_array['host'];

            // �ۥ���̾���ۥ磻�ȥꥹ�Ȥˤ������̵�뤷�Ƽ���URL�Υ����å���
            if (preg_match($whitereg, $hostname)) continue;

            // �ۥ���̾��֥�å��ꥹ�ȤȾȤ餷��碌
            if (preg_match($reg, $hostname)) {
                $this->message .= 'urlbl(name) ';
                return TRUE;
            }
            // �ۥ���̾��IP��֥�å��ꥹ�ȤȾȤ餷��碌
            if ($iplist = gethostbynamel($hostname)) {
                foreach ($iplist as $ip) {
                    if (preg_match($reg, $ip)) {
                        $this->message .= 'urlbl(ip) ';
                        return TRUE;
                    }
                }
            }
            else {
                // IP�����Ĥ����ʤ��ä��Ȥ��ˤ���ݤ�����
                if (SPAM_FILTER_URLBL_UNKNOWN) {
                    $this->message .= 'urlbl(unknown) ';
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    // URL�Υ����ФΤ���������å�
    function urlcountry($reg = SPAM_FILTER_URLCOUNTRY_REG,
                        $whitereg = SPAM_FILTER_URLCOUNTRY_WHITEREG,
                        $urlreg = SPAM_FILTER_URLCOUNTRY_URLREG,
                        $pluginnames = SPAM_FILTER_URLCOUNTRY_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // �������URL�����
        preg_match_all($urlreg, $this->post_data['msg'], $urls);
        foreach ($urls[0] as $url) {
            // URL�Υۥ���̾������
            $url_array = parse_url($url);
            $hostname = $url_array['host'];

            // �ۥ���̾���ۥ磻�ȥꥹ�Ȥˤ������̵�뤷�Ƽ���URL�Υ����å���
            if (preg_match($whitereg, $hostname)) continue;

            // �ۥ���̾��IP��֥�å��ꥹ�ȤȾȤ餷��碌
            if ($iplist = gethostbynamel($hostname)) {
                foreach ($iplist as $ip) {
                    $country = $this->get_country_code( $ip );
                    //$tmpmes .= $hostname . ' ' . $ip . ' ' . $country . ', ';
                    if (preg_match($reg, $country)) {
                        $this->message .= 'urlcountry ';
                        return TRUE;
                    }
                }
            }
            else {
                // IP�����Ĥ����ʤ��ä��Ȥ��ˤ���ݤ�����
                if (SPAM_FILTER_URLCOUNTRY_UNKNOWN) {
                    $this->message .= 'urlcountry(unknown) ';
                    return TRUE;
                }
            }
        }

        //        die_message( "mes: $tmpmes" );

        return FALSE;
    }

    // URL��DNSBL�����äƤ��뤫��ǧ
    function urldnsbl($dnss = SPAM_FILTER_URLDNSBL_DNS,
                      $whitereg = SPAM_FILTER_URLDNSBL_WHITEREG,
                      $urlreg = SPAM_FILTER_URLDNSBL_URLREG,
                      $pluginnames = SPAM_FILTER_URLDNSBL_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        $dns_hosts = explode(",", $dnss);

        // �������URL�����
        preg_match_all($urlreg, $this->post_data['msg'], $urls);
        foreach ($urls[0] as $url) {
            // �ۥ���̾���ۥ磻�ȥꥹ�Ȥˤ������̵�뤷�Ƽ���URL�Υ����å���
            if (preg_match($whitereg, $url)) continue;

            // URL�Υۥ���̾����ɥᥤ�������
            $url_array = parse_url($url);
            $hostname = $url_array['host'];
            // �ɤ�����DNSBL����Ͽ����Ƥ���
            foreach ($dns_hosts as $dns) {
                $lookup = $hostname . '.' . $dns;
                $result = gethostbyname($lookup);
                if ($result != $lookup) {
                    $this->message .= 'urldnsbl ';
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    // URL��NS���֥�å��ꥹ�Ȥ����äƤ��뤫��ǧ
    function urlnsbl($reg = SPAM_FILTER_URLNSBL_REG,
                     $whitereg = SPAM_FILTER_URLNSBL_WHITEREG,
                     $urlreg = SPAM_FILTER_URLNSBL_URLREG,
                     $pluginnames = SPAM_FILTER_URLNSBL_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // �������URL�����
        preg_match_all($urlreg, $this->post_data['msg'], $urls);
        foreach ($urls[0] as $url) {
            // URL�Υۥ���̾������
            $url_array = parse_url($url);
            $hostname = $url_array['host'];

            // �ۥ���̾���ۥ磻�ȥꥹ�Ȥˤ������̵�뤷�Ƽ���URL�Υ����å���
            if (preg_match($whitereg, $hostname)) continue;

            // �ɥᥤ���NS������
            if ($this->dns_get_ns($hostname, $nslist)) {
                // �ɥᥤ���NS������줿��NS�֥�å��ꥹ�ȤȾȤ餷��碌
                foreach ($nslist as $ns) {
                    if (preg_match($reg, $ns)) {
                        $this->message .= 'urlnsbl(name) ';
                        return TRUE;
                    }
                    // NS��IP��֥�å��ꥹ�ȤȾȤ餷��碌
                    if ($iplist = gethostbynamel($ns)) {
                        foreach ($iplist as $ip) {
                            if (preg_match($reg, $ip)) {
                                $this->message .= 'urlnsbl(ip) ';
                                return TRUE;
                            }
                        }
                    }
                }
            }
            else {
                // NS�������ʤ��ä�
                if (SPAM_FILTER_URLNSBL_NSUNKNOWN) {
                    $this->message .= 'urlnsbl(unknown) ';
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    // URL��NS�ι������å�
    function urlnscountry($reg = SPAM_FILTER_URLNSCOUNTRY_REG,
                          $whitereg = SPAM_FILTER_URLNSCOUNTRY_WHITEREG,
                          $urlreg = SPAM_FILTER_URLNSCOUNTRY_URLREG,
                          $pluginnames = SPAM_FILTER_URLNSCOUNTRY_PLUGIN_NAME)
    {
        if (!$this->check_plugin($pluginnames)) return FALSE;

        // �������URL�����
        preg_match_all($urlreg, $this->post_data['msg'], $urls);
        foreach ($urls[0] as $url) {
            // URL�Υۥ���̾������
            $url_array = parse_url($url);
            $hostname = $url_array['host'];

            // �ۥ���̾���ۥ磻�ȥꥹ�Ȥˤ������̵�뤷�Ƽ���URL�Υ����å���
            if (preg_match($whitereg, $hostname)) continue;

            // �ɥᥤ���NS������
            if ($this->dns_get_ns($hostname, $nslist)) {
                // �ɥᥤ���NS������줿�餽�ι��Ĵ�٤ơ��񥳡��ɤȾȤ餷��碌
                foreach ($nslist as $ns) {
                    $country = $this->get_country_code( gethostbyname($ns) );
                    if (preg_match($reg, $country)) {
                        $this->message .= 'urlnscountry ';
                        return TRUE;
                    }
                }
            }
            else {
                // NS�������ʤ��ä�
                if (SPAM_FILTER_URLNSBL_NSUNKNOWN) {
                    $this->message .= 'urlnscountry(unknown) ';
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    // Akismet �ˤ������å�
    function akismet($pluginnames = SPAM_FILTER_AKISMET_PLUGIN_NAME)
    {
        // ������http://note.sonots.com/?PukiWiki%2Fakismet.inc.php���ͤ˽�������

        if (!$this->check_plugin($pluginnames)) return FALSE;

        // akismet���饹���ɤ߹���
        require_once 'akismet.class.php';

        // Post�ǡ�����Ϣ�뤹�롣
        $ignore_post_keys = explode(",", SPAM_FILTER_AKISMET_IGNORE_KEY);
        foreach ($this->post_data as $key => $val) {
            // ignore_post_keys�����ꤵ��Ƥ���Post�ǡ�����Akismet������ʤ�
            if (!in_array($key, $ignore_post_keys)) {
                $body = $body . $val;
            }
        }

        // Akismet����������ǡ������������
        $comment = array();
        $comment['author']    = '';
        $comment['email']     = '';
        $comment['website']   = '';
        $comment['permalink'] = '';
        $comment['body'] = $body;

        $akismet = new Akismet(URL, SPAM_FILTER_AKISMET_API_KEY, $comment);

        if ($akismet->isSpam() == '1') {
            $this->message .= 'akismet ';
            return TRUE;
        }

        return FALSE;
    }

    // CAPTCHA�Ǥ����Ϥ���äƤ��뤫�����å�
    function captcha_check()
    {
        // reCAPTCHA �ǤΥ����å�
        if ($_POST["recaptcha_response_field"]) {
            $resp = recaptcha_check_answer (SPAM_FILTER_RECAPTCHA_PRIVATEKEY,
                                            $_SERVER["REMOTE_ADDR"],
                                            $_POST["recaptcha_challenge_field"],
                                            $_POST["recaptcha_response_field"]);

            if ($resp->is_valid) return TRUE;
        }

        return FALSE;
    }

    // CAPTCHA�Ǥ����Ϥ����
    function captcha_show()
    {
        // reCAPTCHA �Ǥ�CAPTCHA��ɽ��
        global $vars;

        $page = $vars['page'];
        $form .= "<form action='' method='post'>\n";
        $form .= "������Ĥ�ñ������Ϥ��Ƥ���������\n";
        $form .= recaptcha_get_html(SPAM_FILTER_RECAPTCHA_PUBLICKEY);
        foreach ($_POST as $key => $val) {
            if ($key == 'recaptcha_response_field' or
                $key == 'recaptcha_challenge_field') continue;
            $form .= ' <input type="hidden" name="' . $key . '" value="' . htmlspecialchars($val) . '" />' . "\n";
        }
        $form .= ' <input type="hidden" name="page" value="' . htmlspecialchars($page) . '" />' . "\n";
        $form .= ' <input type="submit" name="" value="ǧ��" /><br />' . "\n";
        $form .= '</form>' . "\n";

        die_message( "Spam check failed. Plugin:". $this->plugin_name ." Match:". $this->message ."<br>\n". $form );
    }

    // get DNS server for Windows XP SP2, Vista SP1
    function getDNSServer()
    {
        @exec('ipconfig /all', $ipconfig);
        //print_a($ipconfig, 'label:nameserver');
        foreach ($ipconfig as $line) {
            if (preg_match('/\s*DNS .+:\s+([\d\.]+)$/', $line, $nameservers)) {
                $nameserver = $nameservers[1];
            }
        }
        if (empty($nameserver)) {
            die_message('Can not lookup your DNS server');
        }
        //print_a($nameserver, 'label:nameserver');
        return $nameserver;
    }
    
    //// �ۥ���̾����NS�������������Ѵؿ�
    // hostname�Υɥᥤ���NS��ꥹ��($ns_array)���֤�
    // �����ʤ��ä����ϴؿ����֤��ͤ�FALSE
    // ��PHP4�ξ�硢nslookup ���ޥ�ɤ��Ȥ���ɬ�פ���
    function dns_get_ns( $hostname, &$ns_array )
    {
        // �������֤��Ȥ���򥯥ꥢ���Ƥ���
        if (!empty($ns_array)) while (array_pop($ns_array));

        // �ޤ�����å��夬�ʤ���а�����������̤Υ���å���ե�������ɤ߹���
        if (empty($this->dns_get_ns_cache)) {
            $fp = fopen(DATA_HOME . SPAM_FILTER_DNSGETNS_CACHE_FILE, "a+")
                or die_message('Cannot read dns_get_ns cache file: '. SPAM_FILTER_DNSGETNS_CACHE_FILE ."\n");
            flock($fp, LOCK_SH);
            while ($csv = fgetcsv($fp, 1000, ",")) {
                $host = array_shift($csv);
                $time = $csv[0];
                if ($time + SPAM_FILTER_DNSGETNS_CACHE_DAY*24*60*60 < time())
                    continue; // �Ť��������ϼΤƤ�
                $this->dns_get_ns_cache["$host"] = $csv;
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        // ����å���η�̤����äƤ�ʤ餽�������̤�������֤�
        $cache = $this->dns_get_ns_cache["$hostname"];
        if(!empty($cache)) {
            $time = array_shift($cache);
            foreach($cache as $ns) {
                $ns_array[] = $ns;
            }
            return TRUE;
        }

        // �ۥ���̾��夫���ĤŤĸ��餷��NS��������ޤǻ
        // ��: www.subdomain.example.com��subdomain.example.com��example.com
        $domain_array = explode(".", $hostname);
        $ns_found = FALSE;
        do {
            $domain = implode(".", $domain_array);

            // �Ķ��ǻȤ�����ʤ˹�碌�ƥɥᥤ���NS������
            if (function_exists('dns_get_record')) {
                // �����ؿ� dns_get_record �Ȥ�����
                $lookup = dns_get_record($domain, DNS_NS);
                if (!empty($lookup)) {
                    foreach ($lookup as $record) {
                        $ns_array[] = $record['target'];
                    }
                    $ns_found = TRUE;
                }
            }
            else if (include_once('Net/DNS.php')) {
                // PEAR��DNS���饹���Ȥ�����
                $resolver = new Net_DNS_Resolver();
                if (SPAM_FILTER_IS_WINDOWS) $resolver->nameservers[0] = $this->getDNSServer();
                $response = $resolver->query($domain, 'NS');
                if ($response) {
                    foreach ($response->answer as $rr) {
                        if ($rr->type == "NS") {
                            $ns_array[] = $rr->nsdname;
                        }
                        else if ($rr->type == "CNAME") {
                            // CNAME����Ƥ�Ȥ��ϡ����ä���Ƶ��ǰ���
                            $this->dns_get_ns($rr->rdatastr(), $ns_array);
                        }
                    }
                    $ns_found = TRUE;
                }
            }
            else {
                // PEAR��Ȥ��ʤ���硢�������ޥ��nslookup�ˤ��NS�����
                is_executable(SPAM_FILTER_NSLOOKUP_PATH)
                    or die_message("Cannot execute nslookup. see NSLOOKUP_PATH setting.\n");
                @exec(SPAM_FILTER_NSLOOKUP_PATH . " -type=ns " . $domain, $lookup);
                foreach ($lookup as $line) {
                    if( preg_match('/\s*nameserver\s*=\s*(\S+)$/', $line, $ns) ||
                        preg_match('/\s*origin\s*=\s*(\S+)$/', $line, $ns) ||
                        preg_match('/\s*primary name server\s*=\s*(\S+)$/', $line, $ns) ) {
                        $ns_array[] = $ns[1];
                        $ns_found = TRUE;
                    }
                }
            }
        } while (!$ns_found && array_shift($domain_array) != NULL);

        // NS�������Ƥ����顢��̤򥭥�å�����������¸
        if ($ns_found) {
            // ��̤򥭥�å������Ͽ
            $cache = $ns_array;
            array_unshift($cache, time()); // ���������֤��ݻ�
            $this->dns_get_ns_cache["$hostname"] = $cache;

            // ����å����ե��������¸
            $fp = fopen(DATA_HOME . SPAM_FILTER_DNSGETNS_CACHE_FILE, "w")
                or die_message("Cannot write dns_get_ns cache file: ". SPAM_FILTER_DNSGETNS_CACHE_FILE ."\n");
            flock($fp, LOCK_EX);
            foreach ($this->dns_get_ns_cache as $host=>$cachedata) {
                $csv = $host;
                foreach ($cachedata as $data) {
                    $csv .= ",". $data;
                }
                $csv .= "\n";
                fputs($fp, $csv);
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        return $ns_found;
    }

    //// IP���ɥ쥹����񥳡��ɤ������������Ѵؿ�
    // IP���ɥ쥹("10.1.2.3"�ߤ�����ʸ����)����JP�Ȥ��ι񥳡��ɤ��֤�
    // �����ʤ��ä�����empty('')���֤�
    // ��APNIC��IP���ꥢ�ȹ���б������ɥե����뤬ɬ��
    // ������ꥫ��IP�ϥꥹ�Ȥ�̵����
    function get_country_code( $ip_string )
    {
        // �ޤ���IP�ꥹ�Ȥ��ɤ�Ǥʤ���Хե�������ɤ߹���ǥ���å��夹��
        if (empty($this->get_country_code_cache)) {
            $fp = fopen( DATA_HOME . SPAM_FILTER_IPCOUNTRY_FILE, "r")
                or die_message('Cannot read country file: ' . SPAM_FILTER_IPCOUNTRY_FILE . "\n");
            while ($csv = fgetcsv($fp, 1000, "|")) {
                // IPv4�����б�
                if ($csv[2] === "ipv4") {
                    $country = $csv[1];
                    $ipstring = $csv[3];
                    $ipranges = explode(".", $ipstring);
                    $iprange = ip2long($ipstring);
                    $mask = 256*256*256*256 - $csv[4];
                    $data = new country_data;
                    $data->country = $country;
                    $data->iprange = $iprange;
                    $data->mask = $mask;
                    // Class A��ޤ��������̵���Τǥȥåפ�256��ʬ�䤷���ݻ�
                    $this->get_country_code_cache["$ipranges[0]"][] = $data;
                }
            }
            fclose($fp);
        }

        $ip = ip2long($ip_string);
        $ranges = explode(".", $ip_string);

        $country_code = '';
        foreach ($this->get_country_code_cache["$ranges[0]"] as $data) {
            if ( $data->iprange == ($ip & $data->mask) ) {
                $country_code = $data->country;
                break;
            }
        }

        return $country_code;
    }

}

// get_country_code ���ݻ����Ƥ���ǡ�����¤
class country_data
{
    var $country;
    var $iprange;
    var $mask;
}

?>
