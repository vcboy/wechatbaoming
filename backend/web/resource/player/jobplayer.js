
var Job = {
	player: null,
	container: null,
	proxyUrl: '', //跨域代理地址
	useFace: false, //是否采用人脸识别系统
	pauseFlag: false,
	st_updating: false, //是否正在提交状态
	//进度条拖曳相关
	p_moving: false,
	p_dx: 0,
	p_moved: false,

	init: function(player, configurl) {
		this.player = player;
		this.container = $(player.el());

		this.init_gui();

		//获取配置
		this.getConfig(configurl).done(function() {
			//人脸识别
			$.when(Job.useFace && Job.face && Job.face.start()).done(function() {
				Job.getXML();

				//事件绑定
				//meta
				Job.player.on('loadedmetadata', Job.metaHandler);
				//Job.player.on($.jPlayer.event.durationchange, Job.durationHandler); //经测试不起作用
				//position
				Job.player.on('timeupdate', Job.positionHandler);
				//全屏切换
				Job.player.on('fullscreenchange', Job.fullscreenHandler);
				//播放状态
				Job.player.on('play', Job.playHandler);
				Job.player.on('pause', Job.pauseHandler);
				Job.player.on('ended', Job.stopHandler);
				//拖动
				Job.player.on('seeked', Job.seekHandler);
				//单击暂停
				//Job.player.on($.jPlayer.event.click, Job.clickHandler);
				//页面离开时更新
				$(window).on('beforeunload', Job.leaveHandler);

				/*
				Job.player.on($.jPlayer.event.progress, function(e) {
					//console.log(e.jPlayer.status.seekPercent);
				});
				*/

				//titletip
				$('#job_titletip').text(Job.conf.scoTitle);

				/*
				$('#job_fullmask').click(function() {
					if (!Job.player.data('jPlayer').status.paused) {
						Job.player.jPlayer("pause");
					} else {
						Job.player.jPlayer("play");
					}
				});
				*/

				//进度条拖曳
				//Job.container.find('.vjs-seek-handle').on('mousedown', Job.slidePbar);
				/*
				//videojs 4.7.1 有bug，在代码最底部修复
				var seekBar = Job.player.controlBar.progressControl.seekBar;
				seekBar.onMouseMove = Job.movePbar;
				seekBar.onMouseUp = Job.upPbar;
				*/

				//进度条提示
				Job.container.find('.vjs-progress-holder').on('mouseenter mousemove', Job.showProgress)
					.mouseleave(Job.hideProgress);

				//document.addEventListener('click', Job.backOnlyHandler, true);
				//$('.jp-seek-bar').click(Job.backOnlyHandler);
				/*
				var fn_seekBar = $.jPlayer.prototype.seekBar;
				$.jPlayer.prototype.seekBar = function(e) {
					if (!Job.backOnlyHandler(e)) return;
					fn_seekBar.call(this, e);
				};
				*/
			});

		}).always(function() {

			//按钮监听
			$('#job_catalog_btn').click(function() {
				if (Job.conf.isShowCatalog) {
					Job.hideCatalog();
				} else {
					Job.showCatalog();
				}
			});
			$('#job_note_btn').click(function() {
				if (Job.conf.isShowNote) {
					Job.hideNote();
				} else {
					Job.showNote();
				}
			});
			$('#job_bug_btn').click(function() {
				if (Job.conf.isShowBug) {
					Job.hideBug();
				} else {
					Job.showBug();
				}
			});
			$('#job_setup_btn').click(function() {
				if (Job.conf.isShowSetup) {
					Job.hideSetup();
				} else {
					Job.showSetup();
				}
			});
			$('#job_subtitle_btn').click(function() {
				if (!Job.conf.subtitleXML) {
					Job.showTip('该视频没有字幕');
					return;
				}
				if (Job.conf.isShowSubtitle) {
					Job.hideSubtitle();
				} else {
					Job.showSubtitle();
				}
			});

			//radio修正
			//$('#job_plugins').on('click', '.radio', function() {
			$('#job_plugins').delegate('.radio', 'click', function() {
				if ($(this).hasClass('gray')) return;
				var radio = $(this).find(':radio');
				radio.prop('checked', true);
				$(this).addClass('on');
				$('#job_plugins :radio[name="'+radio.attr('name')+'"]').each(function() {
					$(this).parent().toggleClass('on', this.checked);
				});
			});

			//拖曳
			$('#quiz_wnd').drag({handle: $('#quiz_wnd .job-wnd-title')});

		});
	},

	init_gui: function() {
		if (navigator.userAgent.indexOf('MSIE 6') > -1 || navigator.userAgent.indexOf('MSIE 7') > -1) this.container.addClass('video-js vjs-default-skin');
		this.container.find('.vjs-fullscreen-control').after($('#job_toggles'));
		this.container.append($('#job_plugins'));
		this.container.find('.vjs-progress-control').append($('#job_progress_tip'));
		this.container.find('.vjs-time-controls,.vjs-time-divider').wrapAll('<div class="job-bcol job-time"><div class="job-bcolbody"></div></div>').eq(0).parent().before('<b class="lcorner png_bg"/>').after('<b class="rcorner png_bg"/>');
		this.container.find('.vjs-fullscreen-control,#job_toggles,.vjs-volume-control,.vjs-mute-control').wrapAll('<div class="job-bcol job-cbar"><div class="job-bcolbody"></div></div>').eq(0).parent().before('<b class="lcorner png_bg"/>').after('<b class="rcorner png_bg"/>');
		var isie6 = navigator.userAgent.indexOf('MSIE 6') > -1;
		if (isie6) {
			$('.vjs-play-control,.vjs-play-control .vjs-control-content,.vjs-fullscreen-control,.vjs-mute-control').addClass('png_bg');
			DD_belatedPNG.fix('.png_bg');
		}
	},

	getConfig: function(url) {
		Job.conf = new Job.ConfCls();

		var dtd = $.Deferred();
		$.ajax(url, {dataType: 'xml'}).done(function(xml) {
			$(xml.documentElement).children().each(function() {
				if (this.tagName == 'fileUrl') {
					$(this).children().each(function() {
						Job.conf.fileUrls[this.tagName] = $(this).text();
					});
				} else {
					if (this.tagName in Job.conf) {
						var t = typeof Job.conf[this.tagName];
						if (t == 'number')
							Job.conf[this.tagName] = Number($(this).text());
						else if (t == 'boolean')
							Job.conf[this.tagName] = Job.utils.cbool($(this).text());
						else
							Job.conf[this.tagName] = $(this).text();
					}
				}
			});
			Job.conf.startTime = Job.conf.lastViewedTime;

			//跨域修正
			if (Job.proxyUrl) {
				$.each(['catalogUrl', 'quizUrl', 'bugUrl', 'subtitleUrl'], function(i, k) {
					var url = Job.conf[k], m;
					if (m = url.match(/https?:\/\/([^\/]+)/)) {
						var host = m[1];
						if (host != location.host) {
							Job.conf[k] = Job.utils.joinUrl(Job.proxyUrl, 'url='+encodeURIComponent(url));
						}
					}
				});
			}

			//修正视频文件地址
			$.getJSON('http://cc.mynep.com.cn/servid.php?callback=?', function(servid) {
				for (var k in Job.conf.fileUrls) {
					if (servid != 'cc1')
						Job.conf.fileUrls[k] = Job.conf.fileUrls[k].replace('cc.mynep.com.cn', servid+'.mynep.com.cn');
					else
						Job.conf.fileUrls[k] = Job.conf.fileUrls[k].replace('cc.mynep.com.cn', 'cc1.mynep.com.cn:8080');
				}
				var fileUrl = Job.conf.fileUrls[Job.conf.fileQuality];
				if (fileUrl) {
					var medias = {src: fileUrl};
					if (fileUrl.match(/\.flv$/i)) {
						/*
						alert('HTML5版暂不支持flv视频！');
						dtd.reject();
						*/
						medias.type = 'video/flv';
					/*
					} else if (fileUrl.match(/\.m3u8$/i)) {
						medias.type = 'application/x-mpegURL';
					*/
					} else {
						medias.type = 'video/mp4';
					}
					Job.player.src(medias);
					dtd.resolve();
				}
			});

		}).fail(function() {
			Job.showTip('配置文件加载出错！');
			dtd.reject();
		});
		return dtd.promise();
	},

	getXML: function() {
		$.when(Job.getQuizXml(), Job.getBugXml(), Job.getSubtitleXml()).always(function() {
			if (Job.conf.startTime) {
				Job.player.one('loadedmetadata', function() {
					Job.player.currentTime(Job.conf.startTime);
				});
			}
			if (Job.conf.autostart)
				Job.player.play();
		});
	},

	getQuizXml: function() {
		if (!Job.conf.quizUrl) {
			return;
		}
		return $.ajax(Job.conf.quizUrl, {dataType: 'xml'}).done(function(xml) {
			//Job.conf.quizXML = xml;
			Job.conf.set_quizXML(xml);
		});
	},

	getBugXml: function() {
		if (!Job.conf.bugUrl) {
			return;
		}
		return $.ajax(Job.conf.bugUrl, {dataType: 'xml'}).done(function(xml) {
			//Job.conf.bugXML = xml;
			Job.conf.set_bugXML(xml);
		});
	},

	getSubtitleXml: function() {
		if (!Job.conf.subtitleUrl) {
			return;
		}
		//支持两种格式
		if (!/\.(\w+)$/.test(Job.conf.subtitleUrl)) return;
		var ext = RegExp.$1.toLowerCase();
		//xml
		if (ext == 'xml') {
			return $.ajax(Job.conf.subtitleUrl, {dataType: 'xml'}).done(function(xml) {
				Job.conf.subtitleXML = xml;
				Job.conf.subtitleType = 'xml';
				Job.conf.set_subtitle();
			});
		//srt
		} else if (ext == 'srt') {
			return $.ajax(Job.conf.subtitleUrl).done(function(txt) {
				Job.conf.subtitleXML = txt;
				Job.conf.subtitleType = 'srt';
				Job.conf.set_subtitle();
			});
		}
	},

	//事件代理
	on: function() {
		var proxy = $('#job_plugins');
		proxy.on.apply(proxy, arguments);
	},
	off: function() {
		var proxy = $('#job_plugins');
		proxy.off.apply(proxy, arguments);
	},
	trigger: function() {
		var proxy = $('#job_plugins');
		proxy.trigger.apply(proxy, arguments);
	},

	fullscreenHandler: function(e) {
		//if (e.jPlayer.options.fullScreen)
		
		//调整各个窗口位置
		Job.conf.isShowCatalog && Job.resizeWnd($('#catalog_wnd'));
		Job.conf.isShowNote && Job.resizeWnd($('#note_wnd'));
		Job.conf.isShowBug && Job.resizeWnd($('#bug_wnd'));
		Job.conf.isShowSetup && Job.resizeWnd($('#setup_wnd'));
		Job.conf.isShowQuiz && Job.centerWnd($('#quiz_wnd'));

		window.fullResize && window.fullResize(e); //IE fullscreen fix
	},

	startUpd: function() {
		if (!Job.conf.updTimer) {
			Job.conf.updStime = (new Date()).getTime();
			Job.conf.updTimer = setInterval(Job.updHandler, 1000);
		}
	},

	stopUpd: function() {
		if (Job.conf.updStime) {
			var stime = (new Date()).getTime();
			Job.conf.updAddTime += (stime - Job.conf.updStime) / 1000;
			Job.conf.updStime = stime;
		}
		clearInterval(Job.conf.updTimer);
		Job.conf.updTimer = null;
	},

	playHandler: function() {
		//计时
		Job.startUpd();
		//隐藏播放图标
		//$('.jp-video-play').hide();
		//继续字幕
		Job.resumeSubtitle();
		//隐藏重播画面
		Job.hideReplay();
	},

	pauseHandler: function() {
		//停止计时
		Job.stopUpd();
		//显示播放图标
		if (!Job.pauseFlag) {
			//$('.jp-video-play').show();
		} else {
			Job.pauseFlag = false;
		}
		//暂停字幕
		Job.pauseSubtitle();
	},

	stopHandler: function() {
		//停止计时
		Job.stopUpd();
		if (!Job.conf.viewFlag) {
			//最后再提交一次学习状态
			Job.updateStatus(true);
		}
		//隐藏播放图标
		//$('.jp-video-play').hide();
		//停止字幕
		Job.hideSubtitle();
		//显示重播画画
		Job.showReplay();
	},

	leaveHandler: function() {
		console.log('leave');
		if (!Job.conf.viewFlag && Job.conf.updTimer) {
			//最后再提交一次学习状态
			Job.updateStatus();
		}
		return undefined;
	},

	seekHandler: function(e) {
		//重载字幕
		Job.reloadSubtitle();
		if (Job.player.paused()) {
			Job.pauseSubtitle();
		}

		//目录窗口时间定位
		if (Job.conf.isShowCatalog) {
			Job.catalogWnd.locate();
		}
		//刷新bugtip
		//Job.refreshBugTip();

		if (!Job.conf.viewFlag) {
			//提交学习状态
			Job.updateStatus();
		}
	},

	clickHandler: function(e) {
		if (!Job.player.paused()) {
			Job.player.pause();
		} else {
			Job.player.play();
		}
	},

	metaHandler: function(e) {
		//Job.conf.totalTime = Job.player.duration();
		if (Job.player.duration()) {
			if (!Job.conf.totalTime) Job.conf.set_totalTime(Job.player.duration());
		}
	},

	/*
	durationHandler: function(e) {
		Job.conf.set_totalTime(Job.player.duration());
	},
	*/

	updHandler: function() {
		//状态提交
		if (!Job.conf.viewFlag) {
			if (Job.conf.updAddTime + (((new Date()).getTime() - Job.conf.updStime) / 1000) >= Job.conf.updInterval) {
				Job.updateStatus();
			}
		}
	},

	positionHandler: function(e) {
		if (!Job.conf.totalTime && Job.player.duration()) {
			Job.conf.set_totalTime(Job.player.duration());  //安卓与flash版的metadata事件中取不到duration
		}
		Job.conf.lastViewedTime = Job.player.currentTime();
		Job.conf.maxViewedTime = Math.max(Job.conf.maxViewedTime, Job.conf.lastViewedTime);

		//测验检测
		Job.checkQuiz();

		//目录窗口定位
		if (Job.conf.isShowCatalog) {
			Job.catalogWnd.locate();
		}
	},

	//暂停（不显示图标）
	pauseSilent: function() {
		Job.pauseFlag = true;
		Job.player.pause();
	},

	checkQuiz: function() {
		//trace("QUIZ...................");
		var currentTime = Math.floor(Job.conf.lastViewedTime);
		if (Job.conf.lastQuizTime == currentTime) return;
		Job.conf.lastQuizTime = currentTime;

		/**检查checkpoint点是否已经查看过*/
		if(!Job.conf.hasCheckOne && currentTime >= Job.conf.cp_one && currentTime < Job.conf.cp_two && ((currentTime - Job.conf.cp_one) <= 1) ){
			console.log("CHECK POINT 1 PASSED!!! "+Job.conf.cp_one+ "秒");
			Job.conf.hasCheckOne = true;
		}
		if(!Job.conf.hasCheckTwo && currentTime >= Job.conf.cp_two && currentTime < Job.conf.cp_three && ((currentTime - Job.conf.cp_two) <= 1) ){
			console.log("CHECK POINT 2 PASSED!!!"+Job.conf.cp_two+ "秒");
			Job.conf.hasCheckTwo = true;
		}
		if(!Job.conf.hasCheckThree && currentTime >= Job.conf.cp_three && ((currentTime - Job.conf.cp_three) <= 1) ){
			console.log("CHECK POINT 3 PASSED!!!"+Job.conf.cp_three+ "秒");
			Job.conf.hasCheckThree = true;
		}
		/**弹出测验窗口，暂停视频播放 */
		if (Job.conf.quizXML) {
			var quizlist = $(Job.conf.quizXML.documentElement).children();
			$.each(Job.conf.quizTimeArray, function(i, t) {
				if(t == currentTime){
					Job.conf.quizIndex = i;
					var question = quizlist.eq(i);
					Job.showQuiz(question);
					return false;
				}
			});
		}

		//检测bugxml
		$.each(Job.conf.bugTimeArray, function(i, t) {
			if(t == currentTime){
				var bug = $(Job.conf.bugXML.documentElement).children().eq(i);
				var time = bug.find('>time').text();
				var content = bug.find('>content').text();
				var bugCont = "视频的" + Job.bugWnd.format_time(time) + "更正：" + content;
				Job.showBugTip(bugCont);
				//clearInterval(_positionInterval);
				Job.conf.bugIndex = i;
				return false;
			}
		});
	},

	showQuiz: function(xml) {
		Job.player.pause();
		Job.conf.quizWin = new Job.QuizWnd(xml);
		Job.popWnd($('#quiz_wnd'));
		Job.conf.isShowQuiz = true;
	},

	closeQuiz: function() {
		Job.player.play();
		Job.removeWnd($('#quiz_wnd'));
		Job.conf.isShowQuiz = false;
		Job.conf.quizFinish = true;
	},

	updateStatus: function(finished) {
		if (Job.st_updating) return;
		if (!finished && Job.conf.lastViewedTime == 0) return; //时间点为0时不提交
		Job.st_updating = true;
		var obj = {};
		obj.userId = Job.conf.userId;
		obj.courseId = Job.conf.courseId;
		obj.scoId = Job.conf.scoId;
		obj.historyId = Job.conf.historyId;
		//obj.addTime = Job.conf.updInterval;//新增学习时间，单位秒
		if (Job.conf.updStime) {
			var stime = (new Date()).getTime();
			Job.conf.updAddTime += (stime - Job.conf.updStime) / 1000;
			Job.conf.updStime = stime;
		}
		obj.addTime = Math.floor(Job.conf.updAddTime);//新增学习时间，单位秒
		Job.conf.updAddTime -= obj.addTime;
		obj.totalTime = Job.conf.totalTime;
		if (finished) {
			obj.finished = 1;
			obj.currentTime = 0;
		} else {
			obj.currentTime = Job.conf.lastViewedTime;//当前时间，可用来更新最后一次访问时间，学习到达最远时间点等
		}
		obj.hasCheckOne = Job.conf.hasCheckOne;
		obj.hasCheckTwo = Job.conf.hasCheckTwo;
		obj.hasCheckThree = Job.conf.hasCheckThree;
		obj.firstUpdate = Job.conf.firstUpdate; //第一次提交时，更新，用于统计学习次数
		Job.conf.firstUpdate = false;
		
		$.ajax(Job.conf.updStatusUrl, {type: 'POST', data: obj, dataType: 'xml'}).done(function(xml) {
			console.log('update status');
			if (xml) {
				var status = Number($(xml.documentElement).find('>status').text());
				if (status == -1) {
					Job.player.pause();
				}
				if (!Job.conf.historyId) {
					var historyId = Number($(xml.documentElement).find('>historyId').text());
					if (historyId) Job.conf.historyId = historyId;
				}
				window.onUpdstatus && window.onUpdstatus(status);
			}
		}).always(function() {
			Job.st_updating = false;
		});
	},

	popWnd: function(wnd) {
		$('#job_mask').show();
		Job.centerWnd(wnd.show());
	},

	removeWnd: function(wnd) {
		$('#job_mask').hide();
		wnd.hide();
	},

	centerWnd: function(wnd) {
		var p = Job.player.isFullscreen() ? $(window) : wnd.offsetParent();
		wnd.css('top', (p.height() - wnd.height()) / 2)
			.css('left', (p.width() - wnd.width()) / 2);
	},

	hideAllWnd: function() {
		Job._quietOn = true;
		this.conf.isShowCatalog && this.hideCatalog();
		this.conf.isShowNote && this.hideNote();
		this.conf.isShowSetup && this.hideSetup();
		this.conf.isShowBug && this.hideBug();
		Job._quietOn = false;
		//Job.refreshBtnState();
	},

	refreshBtnState: function() {
		var btns = ['job_catalog_btn', 'job_note_btn', 'job_bug_btn', 'job_setup_btn', 'job_subtitle_btn'];
		var btnCfgs = ['isShowCatalog', 'isShowNote', 'isShowBug', 'isShowSetup', 'isShowSubtitle'];
		$.each(btns, function(i, id) {
			$('#'+id).toggleClass('on', Job.conf[btnCfgs[i]]);
		});
	},

	resizeWnd: function(wnd) {
		var p = Job.player.isFullscreen() ? $(window) : wnd.offsetParent();
		wnd.css('top', (p.height() - wnd.height()) / 2);
	},

	slideOutWnd: function(wnd) {
		wnd.addClass('show');
	},

	slideInWnd: function(wnd) {
		wnd.removeClass('show');
		/*
		var old_width = wnd.width();
		wnd.animate({width: 0}, function() {
			wnd.hide();
			wnd.width(old_width);
		});
		*/
	},
	/**
	* 取得目录
	*/
	getCatalogXml: function() {
		if (this.conf.catalogUrl == '')
			return;
		return $.ajax(this.conf.catalogUrl, {dataType: 'xml'}).done(function(xml) {
			Job.conf.catalogXML = xml;
		});
	},

	showCatalog: function() {
		this.hideAllWnd();
		if (!this.conf.catalog_init) {
			this.catalogWnd.setSource();
		} else {
			this.catalogWnd.locate();
		}
		this.slideOutWnd($('#catalog_wnd'));
		//$('#catalog_wnd').show();
		this.resizeWnd($('#catalog_wnd'));
		this.conf.isShowCatalog = true;
		Job.refreshBtnState();
	},

	hideCatalog: function() {
		Job.slideInWnd($('#catalog_wnd'));
		Job.conf.isShowCatalog = false;
		Job._quietOn || Job.refreshBtnState();
	},
	/**
	* 取得笔记
	*/
	getNoteXml: function() {
		if (this.conf.viewFlag || this.conf.noteUrl == '')
			return;
		return $.ajax(this.conf.noteUrl, {data: {scoId: Job.conf.scoId}, dataType: 'xml'}).done(function(xml) {
			Job.conf.noteXML = xml;
		});
	},

	showNote: function() {
		this.hideAllWnd();
		if (!this.conf.note_init) {
			this.noteWnd.setSource();
		}
		this.slideOutWnd($('#note_wnd'));
		//$('#note_wnd').show();
		this.resizeWnd($('#note_wnd'));
		this.conf.isShowNote = true;
		Job.refreshBtnState();
	},

	hideNote: function() {
		Job.slideInWnd($('#note_wnd'));
		Job.conf.isShowNote = false;
		Job._quietOn || Job.refreshBtnState();
	},

	showBug: function() {
		this.hideAllWnd();
		if (!this.conf.bug_init) {
			this.bugWnd.setSource();
		} else {
			this.bugWnd.show_init();
		}
		this.bugWnd.showTime();
		this.slideOutWnd($('#bug_wnd'));
		//$('#bug_wnd').show();
		this.resizeWnd($('#bug_wnd'));
		this.conf.isShowBug = true;
		Job.refreshBtnState();
	},

	hideBug: function() {
		Job.slideInWnd($('#bug_wnd'));
		Job.conf.isShowBug = false;
		Job._quietOn || Job.refreshBtnState();
	},

	showBugTip: function(text) {
		Job.bugTip.show(text);
	},

	showTip: function(text) {
		Job.errorTip.show(text);
	},
		
	//显示设置窗口
	showSetup: function(){
		this.hideAllWnd();
		if (!this.conf.setup_init) {
			this.setupWnd.setSource();
		} else {
			this.setupWnd.init_quality();
		}
		this.slideOutWnd($('#setup_wnd'));
		//$('#bug_wnd').show();
		this.resizeWnd($('#setup_wnd'));
		Job.conf.isShowSetup = true;
		Job.refreshBtnState();
	},
	//隐藏设置窗口
	hideSetup: function(){
		Job.slideInWnd($('#setup_wnd'));
		Job.conf.isShowSetup = false;
		Job._quietOn || Job.refreshBtnState();
	},

	//显示字幕
	showSubtitle: function() {
		Job.subtitle.show();
		Job.conf.isShowSubtitle = true;
		Job.refreshBtnState();
	},
	//隐藏字幕
	hideSubtitle: function() {
		Job.subtitle.hide();
		Job.conf.isShowSubtitle = false;
		Job.refreshBtnState();
	},
	//暂停字幕
	pauseSubtitle: function() {
		if (Job.conf.isShowSubtitle)
			Job.subtitle.pause();
	},
	//恢复字幕
	resumeSubtitle: function() {
		if (Job.conf.isShowSubtitle)
			Job.subtitle.resume();
	},
	//重载字幕
	reloadSubtitle: function() {
		if (Job.conf.isShowSubtitle)
			Job.subtitle.reload();
	},

	changeQuality: function(quality) {
		var fileUrl = Job.conf.fileUrls[quality];
		var start_time = Job.conf.lastViewedTime;
		var medias = {src: fileUrl};
		if (fileUrl.match(/\.flv$/i)) medias.type = 'video/flv';
		//else if (fileUrl.match(/\.m3u8$/i)) medias.type = 'application/x-mpegURL';
		else medias.type = 'video/mp4';
		Job.player.src(medias).one('loadedmetadata', function() {
			Job.player.currentTime(start_time);
		}).play();
		Job.conf.fileQuality = quality;
	},

	changeVideo: function(configurl) {
		Job.player.pause();
		Job.hideAllWnd();
		Job.conf.isShowQuiz && Job.removeWnd($('#quiz_wnd'));
		Job.hideSubtitle();
		this.getConfig(configurl).done(function() {
			Job.getXML();

			//titletip
			$('#job_titletip').text(Job.conf.scoTitle);

		});
	},

	showReplay: function() {
		if (!Job.replay.inited) {
			Job.replay.init();
		}
		$('#job_replay').fadeIn();
	},

	hideReplay: function() {
		$('#job_replay').hide();
	},

	/*
	slidePbar: function(e) {
		if (Job.p_moving) return false;

		var handle = $(this);
		Job.p_dx = e.clientX - handle.parent().width();

		$(document).mousemove(Job.movePbar)
			.mouseup(Job.upPbar);
		Job.p_moving = true;
		Job.p_moved = false;

		//isie && this.setCapture();
		return false;
	},
	*/
	movePbar: function(e) {
		var per = this.calculateDistance(e);
		var handle = Job.container.find('.vjs-seek-handle');
		var handlePercent = handle.width() / this.width();
		var adjustedProgress = per * (1 - handlePercent);
		var barProgress = adjustedProgress + (handlePercent / 2);
		handle.css('left', adjustedProgress*100+'%');
		Job.container.find('.vjs-play-progress').width(barProgress*100+'%');
	},
	upPbar: function(e) {
		var per = this.calculateDistance(e);
		if (Job.backOnlyCheck(per)) {
			Job.player.currentTime(Job.conf.totalTime*per);
		}
		//else this.update();
		_seekup.call(this, e);
		//isie && $(copts.handle)[0].releaseCapture();
	},

	showProgress: function(e) {
		/*
		var ret = Job.getSeekPercent(e, true);
		var per = ret[0], offsetX = ret[1];
		if (!per) return;
		*/
		var seekBar = Job.player.controlBar.progressControl.seekBar;
		var per = seekBar.calculateDistance(e);
		var tipBox = $('#job_progress_tip');
		var offsetX = seekBar.width() * per - tipBox.width() / 2 + 8;
		var time = Job.utils.format_time(vjs.round(Job.conf.totalTime * per, 2));
		tipBox.text(time).css('left', offsetX).show();
	},

	hideProgress: function() {
		$('#job_progress_tip').hide();
	},

	backOnlyHandler: function(e) {
		//console.log(e.target);
		//if (e.target.id != 'job_seekbar') return;
		//判断是否backonly
		if (!Job.conf.viewFlag && Job.conf.backOnly && Job.conf.totalTime > 0) {
			//var per = Job.getSeekPercent(e);
			var per = Job.player.controlBar.progressControl.seekBar.calculateDistance(e);
			return Job.backOnlyCheck(per);
		}
		return true;
	},

	backOnlyCheck: function(per) {
		//判断是否backonly
		if (!Job.conf.viewFlag && Job.conf.backOnly && Job.conf.totalTime > 0) {
			if (!per) return true;
			var pos = Job.conf.totalTime * per;
			if (pos > Job.conf.maxViewedTime) {
				Job.showBackOnlyTip();
				return false;
			}
		}
		return true;
	},

	/*
	getSeekPercent: function(e, returnOffset) {
		var pbar = Job.container.find('.vjs-progress-holder');
		var offsetX = Job.utils.getOffsetX(e, pbar);
		//if (e.offsetX == undefined) e.offsetX = Job.utils.getOffsetX(e);
		var boxWidth = pbar.width();
		var per = Math.min((offsetX) / boxWidth, 1);
		if (!per) return 0;
		var handlePercent = Job.container.find('.vjs-seek-handle').width() / boxWidth;
		var per2 = Math.min((per-handlePercent/2) / (1-handlePercent), 1);
		return returnOffset ? [per2, offsetX] : per2;
	},
	*/

	showBackOnlyTip: function() {
		Job.showTip('不能拖动至未看过的内容');
	}
};

Job.ConfCls = function(){
};
Job.ConfCls.prototype = {
	userId: 0, //用户id
	courseId: 0, //课程id
	scoId: 0, //课件id
	scoTitle: '', //课件标题
	historyId: 0, //看课历史id

	fileUrls: {},
	fileQuality: 'normal', //视频质量 normal/high  默认为normal
	autostart: true,  //是否自动开始播放

	totalTime: 0, //播放总时间

	cp_one: 0,
	cp_two: 0,
	cp_three: 0,
	hasCheckOne: false,	//课间播放第一个检查点
	hasCheckTwo: false,	//课件播放第二个检查点
	hasCheckThree: false,	//课件播放第三个检查点

	/**是否只能按顺序查看课程，true：不能往前拖动，要配合maxViewedTime*/
	backOnly: false,
	maxViewedTime: 0, //学习时间的最大值
	lastViewedTime: 0, //最后一次退出的时间点
	startTime: 0, //开始的时间（读取配置中的lastViewedTime）
	updInterval: 30, //每隔几秒钟提交一次学习状态到服务器
	updTimer: null,
	updAddTime: 0,
	updStime: 0,

	isShowCatalog: false, //目录是否已经显示
	isShowNote: false, //笔记是否已经显示
	isShowBug: false, //纠错是否已经显示
	isShowSetup: false, //设置窗口是否已经显示
	isShowSubtitle: false, //字幕是否已经显示
	isShowQuiz: false, //测验是否已经显示

	catalog_init: false,
	note_init: false,
	bug_init: false,
	setup_init: false,

	catalogUrl: "",//目录xml的url
	quizUrl: "",//测验提xml的url
	noteUrl: "",//笔记xml的url
	bugUrl: "",//纠错xml的url
	subtitleUrl: "",//字幕xml的url
	updStatusUrl: "",//更新状态的url
	quizPostUrl: "",//测验提交的url
	bugPostUrl: "",//bug提交的url
	
	firstUpdate: true,//是否是第一次提交，用于更新时判断是否更新用户的学习次数
	
	quizWin: null,
	lastQuizTime: -1,  //测验检查时防止重复
	quizFinish: false,
	quizXML: null,
	quizIndex: 0, //当前是第几个测验，索引数
	quizScore: 0, //课间练习得分
	_quizScores: {}, //课间练习得分
	testScore: 0, //课后练习得分
	catalogXML: null,
	noteXML: null, //笔记XML
	bugXML: null, //纠错XML
	subtitleType: '', //字幕类型
	subtitleXML: null, //字幕XML
	subtitleArray: [], //字幕数组
	
	viewFlag: false,  //是否只是用来预览
	faceFlag: false,  //是否需要人脸识别
	faceUrl: '', //人脸识别上传地址

	//isManualSeek: false, //是否是在目录窗口手动点击跳转
	
	/**
	 * 存放习题播放时间,默认播放时间是经过排序的从小到大排序
	 */
	quizTimeArray: [],
	bugTimeArray: [],
	bugIndex: -1,

	set_totalTime: function(value) {
		this.totalTime = value;
		this.cp_one = (value - 3) / 3;
		this.cp_two = (value - 3) * 2 / 3;
		this.cp_three = value -3;
	},
	/*
	get totalTime() {
		return this._totalTime;
	},
	*/
	//设置测验xml时，同时更新quizTimeArray内容
	set_quizXML: function(xml) {
		this.quizXML = xml;
		$(xml.documentElement).children().each(function() {
			var showTime = Number($(this).attr('showTime'));
			Job.conf.quizTimeArray.push(showTime);
		});
	},
	/*
	get quizXML() {
		return this._quizXML;
	},
	*/
	//设置bugxml时，同时更新bugTimeArray内容
	set_bugXML: function(xml) {
		this.bugXML = xml;
		$(xml.documentElement).children().each(function() {
			var showTime = Number($(this).find('>time').text());
			Job.conf.bugTimeArray.push(showTime);
		});
	},
	/*
	get bugXML() {
		return this._bugXML;
	},
	*/

	set_subtitle: function() {
		var start_time, end_time;
		if (this.subtitleType == 'xml') {
			var source = $(this.subtitleXML.documentElement).children();
			for (var i=0; i<source.length; i++) {
				var item = source.eq(i);
				start_time = Number(item.attr('start'));
				end_time = Number(item.attr('end'));
				this.subtitleArray.push({start: start_time, end: end_time, text: item.text()});
			}
		} else if (this.subtitleType == 'srt') {
			//var p = /^\d+\n(.+)\n((?:.+\n)+)/gm;
			var nl = '(?:\\r\\n|\\r|\\n)';
			var p = new RegExp('^\\d+'+nl+'(.+)'+nl+'((?:.+'+nl+')+)', 'gm'),
				p2 = /(\S+) --> (\S+)/;
			var m, m2;
			while (m = p.exec(this.subtitleXML)) {
				m2 = m[1].match(p2);
				start_time = Job.utils.c2time(m2[1]);
				end_time = Job.utils.c2time(m2[2]);
				var subtitle = $.trim(m[2]);
				this.subtitleArray.push({start: start_time, end: end_time, text: subtitle});
			}
		}
	},

	set_quizScore: function(score, isTest) {
		if (!isTest) {
			this._quizScores[this.quizIndex] = Math.max(this._quizScores[this.quizIndex] || 0, score);
			var scores = 0;
			for (var i in this._quizScores) {
				scores += this._quizScores[i];
			}
			this.quizScore = scores;
		} else {
			this.testScore = Math.max(this.testScore, score);
		}
	}

	/*
	get quizScore() {
		return this._quizScore;
	}
	*/
};

Job.utils = {
	cbool: function(v) {
		if (!v) return false;
		return v != 'false';
	},

	joinUrl: function(url, param) {
		return url + (url.indexOf('?') > -1 ? '&' : '?') + param;
	},

	format_time: function(time) {
		var min = Math.floor(time/60);
		var sec = Math.floor(time%60);  
		var timeResult = (min < 10 ? "0"+min.toString() : min.toString()) + ":" + (sec < 10 ? "0"+sec.toString() : sec.toString());
		return timeResult;
	},

	//srt字幕时间转换
	c2time: function(tstr) {
		var p = /(\d+):(\d+):(\d+),(\d+)/;
		var t = tstr.match(p);
		return parseInt(t[1])*60*60 + parseInt(t[2])*60 + parseInt(t[3]) + parseInt(t[4])/1000;
	},

	init_radio: function(selector) {
		$(selector).each(function() {
			$(this).parent().toggleClass('on', $(this).prop('checked'));
		});
	},

	getOffsetX: function(e, p) {
		p = p || e.target;
		return e.pageX - $(p).offset().left;
	}
};

Job.catalogWnd = {
	inited: false,
	init: function() {
		this.inited = true;
		$('#catalog_wnd>.job-slidein-btn').click(Job.hideCatalog);
		//目录点击
		//$('#job_catalist').on('click', 'a', function() {
		$('#job_catalist').delegate('a', 'click', function() {
			var time = Number($(this).attr('time'));
			if (!Job.conf.viewFlag && Job.conf.backOnly && time > Job.conf.maxViewedTime) {
				Job.showBackOnlyTip();
				return;
			}
			Job.player.currentTime(time);
		});
	},

	setSource: function() {
		if (!this.inited) this.init();
		$('#job_catalist').html('');
		$.when(Job.getCatalogXml()).done(function() {
			Job.conf.catalog_init = true;
			if (Job.conf.catalogXML) {
				var html = '';
				$(Job.conf.catalogXML.documentElement).children().each(function() {
					var time = Number($(this).attr('time'));
					var min = Math.floor(time/60);
					var sec = Math.floor(time%60);  
					var timeResult = (min < 10 ? "0"+min.toString() : min.toString()) + ":" + (sec < 10 ? "0"+sec.toString() : sec.toString());
					var showText = $(this).attr('label') + " (" + timeResult+")";
					html += '<li><a href="javascript:;" time="'+time+'" title="'+$.trim(showText)+'">'+showText+'</a></li>';
				});
				$('#job_catalist').html(html);
				Job.catalogWnd.locate();
			}
		});
	},

	locate: function() {
		if (!Job.conf.catalogXML) return;
		this.updateColor();

		var cur_time = Math.round(Job.conf.lastViewedTime);
		var last_idx = -1;
		var found = false;
		//for each (var item:XML in model.catalogXML) {
		$(Job.conf.catalogXML.documentElement).children().each(function(n) {
			var time = Number($(this).attr('time'));
			if (cur_time >= time) {
				last_idx = n;
				return;
			}
			found = true;
			if (last_idx > -1) {
				Job.catalogWnd.setSel(last_idx);
				return false;
			}
		});
		if (!found && last_idx > -1) {
			this.setSel(last_idx);
		}
	},

	setSel: function(i) {
		$('#job_catalist>li').eq(i).find('>a').addClass('current').end().siblings().find('>a').removeClass('current');
	},

	updateColor: function() {
		$('#job_catalist>li>a').each(function() {
			var mt = Job.conf.maxViewedTime;
			var time = Number($(this).attr('time'));
			if (time <= mt) $(this).addClass('viewed');
			else return false;
		});
	}
};

Job.noteWnd = {
	inited: false,
	init: function() {
		this.inited = true;
		$('#note_wnd>.job-slidein-btn').click(Job.hideNote);
		//点击
		//$('#job_notelist').on('click', 'a.job-note-itm', function() {
		$('#job_notelist').delegate('a.job-note-itm', 'click', function() {
			var time = Number($(this).attr('time'));
			Job.player.currentTime(time);
		});
		//删除按钮
		//$('#job_notelist').on('click', 'a.job-del-btn', function() {
		$('#job_notelist').delegate('a.job-del-btn', 'click', function() {
			if (!confirm('确定要删除这个笔记项吗？')) return;
			$.ajax(Job.conf.noteUrl, {data: {
					act: 'del',
					noteId: $(this).parent().attr('noteid'),
					userId: Job.conf.userId,
					scoId: Job.conf.scoId
				}, dataType: 'xml'}).done(function(xml) {
				Job.conf.noteXML = xml;
				Job.noteWnd.setSource();
			});
		});
		//添加按钮
		$('#job_addnote').click(function() {
			var text = $.trim($('#job_notetxt').val());
			if (!text) {
				alert('笔记的内容不可为空');
				return;
			}
			$.ajax(Job.conf.noteUrl, {type: 'POST', data: {
					act: 'add',
					userId: Job.conf.userId,
					scoId: Job.conf.scoId,
					courseId: Job.conf.courseId,
					note: text,
					note_time: Job.conf.lastViewedTime
				}, dataType: 'xml'}).done(function(xml) {
				Job.conf.noteXML = xml;
				Job.noteWnd.setSource();
				$('#job_notetxt').val('');
			});
		});
	},

	setSource: function() {
		if (!this.inited) this.init();
		$('#job_notelist').html('');
		$.when(Job.getNoteXml()).done(function() {
			Job.conf.note_init = true;
			if (Job.conf.noteXML) {
				var html = '';
				$(Job.conf.noteXML.documentElement).children().each(function() {
					var time = Number($(this).attr('time'));
					var timeResult = Job.utils.format_time(time);
					var showText = " (" + timeResult+")";
					html += '<li noteid="'+$(this).attr('noteId')+'"><a href="javascript:;" class="job-note-itm" time="'+time+'" title="单击跳转到笔记记录位置">'+$(this).attr('label')+'<span>'+showText+'</span></a><a href="javascript:;" class="job-del-btn"></a></li>';
				});
				$('#job_notelist').html(html);
			}
		});
	}
};

Job.bugWnd = {
	inited: false,
	currentIndex: 0,
	init: function() {
		this.inited = true;
		$('#bug_wnd>.job-slidein-btn').click(Job.hideBug);
		//上一条/下一条
		$('#job_prevbug').click(function() {
			if ($(this).hasClass('gray')) return;
			var index = Job.bugWnd.currentIndex - 1;
			Job.bugWnd.showBug(index);
		});
		$('#job_nextbug').click(function() {
			if ($(this).hasClass('gray')) return;
			var index = Job.bugWnd.currentIndex + 1;
			Job.bugWnd.showBug(index);
		});
		//提交按钮
		$('#job_subbug').click(function() {
			var bug_time = $('#job_bug_time').val();
			var bug_content = $.trim($('#job_bug_content').val());
			if (!bug_content) {
				alert('纠错内容不可为空');
				return;
			}
			if (!bug_time) {
				alert('纠错时间不可为空');
				return;
			}
			$.ajax(Job.conf.bugPostUrl, {type: 'POST', data: {
					act: 'add',
					userId: Job.conf.userId,
					scoId: Job.conf.scoId,
					courseId: Job.conf.courseId,
					bug_content: bug_content,
					bug_time: bug_time
				}}).done(function() {
				$('#job_bug_content').val('');
				$('#bug_form').hide();
				$('#bug_history').show();
				alert("谢谢您的意见，我们会尽快处理，处理结果可以在课程纠错模块中查询");
			});
		});
		$('#job_viewbug').click(function() {
			$('#bug_form').hide();
			$('#bug_history').show();
		});
	},

	setSource: function() {
		if (!this.inited) this.init();
		$('#job_bugshow').text('');
		Job.conf.bug_init = true;
		this.show_init();
		if (!Job.conf.bugXML) {
			$('#job_prevbug').addClass('gray');
			$('#job_nextbug').addClass('gray');
		} else {
			this.showBug(0);
		}
	},

	show_init: function() {
		if (!Job.conf.viewFlag) {
			$('#bug_form').show();
			$('#bug_history').hide();
		} else {
			$('#bug_form').hide();
			$('#bug_history').show();
		}
	},

	showBug: function(index) {
		if (!Job.conf.bugXML) return;
		var list = $(Job.conf.bugXML.documentElement).children();
		if (index < 0) {
			alert('已到第一条');
			return;
		}
		if (index >= list.length) {
			alert('已到最后一条');
			return;
		}
		if (index < 0 || index >= list.length) return;
		var item = list.eq(index);
		var time = item.find('>time').text();
		var content = item.find('>content').text();
		currentBug = "视频的" + Job.bugWnd.format_time(time) + "更正：" + content;
		$('#job_bugshow').text(currentBug);
		Job.bugWnd.currentIndex = index;
		//检查是否已到第一条或最后一条
		if (index == 0) {
			$('#job_prevbug').addClass('gray');
		} else {
			$('#job_prevbug').removeClass('gray');
		}
		if (index == list.length - 1) {
			$('#job_nextbug').addClass('gray');
		} else {
			$('#job_nextbug').removeClass('gray');
		}
	},

	format_time: function(time) {
		var min = Math.floor(time/60);  
		var sec = Math.floor(time%60);  
		var timeResult = (min < 10 ? "0"+min.toString() : min.toString()) + "分" + (sec < 10 ? "0"+sec.toString() : sec.toString() + "秒");
		return timeResult;
	},

	showTime: function() {
		var time = Math.round(Job.conf.lastViewedTime);
		$('#job_bug_time').val(this.format_time(time));
	}
};

Job.bugTip = {
	inited: false,
	init: function() {
		this.inited = true;
		$('#job_bugtip>.job-tclose-btn').click(this.showFade);
	},

	/*
	get text() {
		return $('#job_bugtip_txt').text();
	},
	set text(s) {
		$('#job_bugtip_txt').text(s);
	},
	*/
	show: function(text) {
		if (!Job.bugTip.inited) Job.bugTip.init();
		$('#job_bugtip').stop().show();
		//Job.bugTip.text = text;
		$('#job_bugtip_txt').text(text);
	},
	showFade: function() {
		$('#job_bugtip').fadeOut();
	}
};

Job.subtitle = {
	inited: false,
	showTimer: null,
	hideTimer: null,
	source: null,

	init: function() {
		this.inited = true;
		this.source = Job.conf.subtitleArray;
	},

	/*
	get text() {
		return $('#job_subtitle').text();
	},
	set text(txt) {
		$('#job_subtitle').text(txt);
	},
	*/

	showHandler: function() {
		Job.subtitle.showTimer = null;
		$('#job_subtitle').show();
		var item = Job.subtitle.source[Job.subtitle.idx];
		var end_time = item.end;
		var cur_time = Job.conf.lastViewedTime;
		//Job.subtitle.text = item.text();
		$('#job_subtitle').text(item.text);
		var delay = (end_time - cur_time) * 1000;
		if (delay <= 0) {
			//console.log(end_time, cur_time);
			Job.subtitle.idx++;
			hideHandler();
			return;
		}
		if (!Job.player.paused()) {
			Job.subtitle.idx++;
			Job.subtitle.hideTimer = setTimeout(Job.subtitle.hideHandler, delay);
		}
	},
	hideHandler: function() {
		Job.subtitle.hideTimer = null;
		$('#job_subtitle').hide();
		Job.subtitle.showNext();
	},
	showNext: function() {
		//确定在字幕的哪个位置
		var cur_time = Job.conf.lastViewedTime;
		for (var i=this.idx; i<this.source.length; i++) {
			var item = this.source[i];
			//console.log(item);
			var start_time = item.start;
			var end_time = item.end;
			//console.log(start_time, end_time, cur_time);
			if (cur_time < start_time) {
				this.idx = i;
				if (!Job.player.paused())
					this.showTimer = setTimeout(this.showHandler, (start_time - cur_time) * 1000);
				break;
			}
			if (cur_time < end_time) {
				this.idx = i;
				this.showHandler();
				break;
			}
		}
	},
	show: function() {
		if (!this.inited) this.init();
		this.idx = 0;
		//resize();
		this.showNext();
	},
	hide: function() {
		if (this.showTimer) {
			clearTimeout(this.showTimer);
			this.showTimer = null;
		}
		if (this.hideTimer) {
			clearTimeout(this.hideTimer);
			this.hideTimer = null;
		}
		$('#job_subtitle').hide();
	},
	
	//暂停
	pause: function() {
		if (this.showTimer) {
			clearTimeout(this.showTimer);
			this.showTimer = null;
		}
		if (this.hideTimer) {
			clearTimeout(this.hideTimer);
			this.hideTimer = null;
			this.idx && this.idx--;
		}
	},
	//恢复
	resume: function() {
		this.showNext();
	},
	//重载
	reload: function() {
		if (this.showTimer) {
			clearTimeout(this.showTimer);
			this.showTimer = null;
		}
		if (this.hideTimer) {
			clearTimeout(this.hideTimer);
			this.hideTimer = null;
		}
		$('#job_subtitle').hide();
		this.idx = 0;
		this.showNext();
	}
};

Job.setupWnd = {
	inited: false,
	init: function() {
		this.inited = true;
		$('#setup_wnd>.job-slidein-btn').click(Job.hideSetup);
		$('#job_setqua').click(this.submit);
		$('#job_cancelqua').click(Job.hideSetup);
	},

	setSource: function() {
		if (!this.inited) this.init();
		$('#job_qualist').html('');
		Job.conf.setup_init = true;
		var labels = [['normal', '标清'], ['high', '高清'], ['mobile', '移动']];
		$.each(labels, function(i, label) {
			if (!Job.conf.fileUrls[label[0]]) return;
			var radio = '<li><label class="radio"><input type="radio" name="quality" value="'+label[0]+'"/>'+label[1]+'</label></li>';
			$('#job_qualist').append(radio);
		});
		this.init_quality();
	},

	init_quality: function() {
		$('#setup_form :radio').val([Job.conf.fileQuality]);
		Job.utils.init_radio('#setup_form :radio');
	},

	submit: function() {
		var new_quality = $('#setup_form :checked').val();
		if (!new_quality) return;
		if (new_quality != Job.conf.fileQuality) {
			Job.changeQuality(new_quality);
		}
		Job.hideSetup();
	}
};

Job.QuizWnd = function(xml) {
	if (!$('#quiz_wnd').data('inited')) Job.QuizWnd.init();
	//if (!Job.QuizWnd.inited) Job.QuizWnd.init();
	this.setSource(xml);
};
Job.QuizWnd.inited = false;
Job.QuizWnd.init = function() {
	//Job.QuizWnd.inited = true;
	$('#quiz_wnd').data('inited', true);
	function proxy(fname) {
		return function() {
			Job.conf.quizWin[fname]();
		};
	}
	if (Job.conf.viewFlag)
		$('#quiz_wnd .job-close-btn').click(Job.closeQuiz);
	else
		$('#quiz_wnd .job-close-btn').click(proxy('skip'));
	$('#job_quizsub').click(proxy('submit'));
	$('#job_quizskip').click(proxy('skip'));
	$('#job_quizview').click(proxy('review'));
	$('#job_quizreset').click(proxy('reset'));
	$('#job_quizfinish').click(proxy('finish'));
};
Job.QuizWnd.prototype = {
	quizNum: 0,
	quizCorrectNum: 0,
	allPass: false,
	quizScore: 0,
	isTest: false,
	canSkip: false,
	questionsXml: null,
	copyXml: null,

	setSource: function(xml) {
		this.questionsXml = xml;
		this.copyXml = this.questionsXml.children();
		this.isTest = this.questionsXml.attr('isTest') == 'true' ? true : false;
		this.canSkip = this.questionsXml.attr('canSkip') == 'true' ? true : false;
		$('#quiz_title').text(this.isTest ? "课后习题" : "课间习题");
		this.createPageOne();
		this.setSelectedPage(0);
	},

	createPageOne: function() {
		var html = '';
		this.copyXml.each(function(n) {
			var type = $(this).attr('type');
			var content = $(this).find('>content').text();
			html += '<li><dl score="'+$(this).attr('score')+'"><dt>'+content+'</dt>';
			var options = $(this).find('>answer>option');
			options.each(function(i) {
				var groupName;
				if(type=="S"||type=="P"){//单选题
					groupName = 'opt_'+n;
					html += '<dd><label class="radio"><input type="radio" name="'+groupName+'" value="'+i+'" flag="'+$(this).attr('flag')+'" />'+$(this).text()+'</label></dd>';
				}else if(type == "M"){
					groupName = 'opt_'+n+'[]';
					html += '<dd><label><input type="checkbox" name="'+groupName+'" value="'+i+'" flag="'+$(this).attr('flag')+'" />'+$(this).text()+'</label></dd>';
				}
			});
			html += '</dl></li>';
		});
		$('#job_quizlist').html(html);
		Job.utils.init_radio('#job_quizlist :radio');
	},

	setSelectedPage: function(index, pass) {
		this.showPage(index);
		if(index==0){//做题页面
			this.setShow(true, 'job_quizsub', 'job_quizskip');
			this.setShow(false, 'job_quizview', 'job_quizfinish', 'job_quizreset');
		}else if(index == 1){//查看测试结果页面
			this.setShow(true, 'job_quizview');
			this.setShow(false, 'job_quizsub', 'job_quizskip');
			if(pass){
				this.setShow(true, 'job_quizfinish');
				this.setShow(false, 'job_quizreset');
			}else{
				this.setShow(true, 'job_quizreset');
				this.setShow(false, 'job_quizfinish');
			}
		}else if(index == 2){
			this.setShow(false, 'job_quizsub', 'job_quizskip', 'job_quizview');
			this.setShow(true, 'job_quizfinish', 'job_quizreset');
		}
	},

	showPage: function(index) {
		$('#job_quiz_box').children().eq(index).show().siblings().hide();	
	},

	setShow: function(visible) {
		$.each($.makeArray(arguments).slice(1), function(i, id) {
			$('#'+id).toggle(visible);
		});
	},

	testResult: function() {
		var pass = true;
		this.quizNum = this.copyXml.length;
		var num = 0;
		var scores = 0;
		$('#job_quizlist dl').each(function() {
			var correct = true;
			var options = $(this).find('input');
			var score = Number($(this).attr('score')) || 0;
			options.each(function() {
				var flag = $(this).attr('flag');
				var selected = $(this).prop('checked') ? 'Y' : 'N';
				if(flag != selected){
					correct = false;
					return false;
				}
			});
			if(correct) {
				num++;
				scores += score;
			}
		});
		this.quizCorrectNum = num;
		this.quizScore = scores;
		var perc = Number((this.quizCorrectNum/this.quizNum).toFixed(2));
		$('#quiz_resultLabel').text("您的成绩："+this.quizScore+"分 "+this.quizCorrectNum+"/"+ this.quizNum + " ("+perc*100 +"%)");
		//做到这里
		if(perc < 1){
			$('#quiz_infoLabel').removeClass('success').text('很遗憾，您没有通过达标分数！');
			$('#quiz_tipLabel').text('您可以点击"查看答案"进行回顾，或者点击"重做"。');
			pass = false;
		}else{
			$('#quiz_infoLabel').addClass('success').text('恭喜您通过达标分数！');
			$('#quiz_tipLabel').text('您可以点击"查看答案"进行回顾，或者点击"完成"。');
			pass = true;
		}
		return pass;
	},

	submit: function(){
		var pass = this.testResult();
		this.allPass = (this.allPass || pass);//是否通过，只要通过一次，就算通过，可以跳过或者完成
		this.setSelectedPage(1,pass);
	},
	reset: function(){
		$('#job_quiz_form')[0].reset();
		Job.utils.init_radio('#job_quizlist :radio');
		this.setSelectedPage(0);
	},
	skip: function(){
		if(this.canSkip || this.allPass){
			Job.closeQuiz();
		}else{
			alert("不能跳过习题，请正确完成答题！");
		}
		
	},
	finish: function(){
		if(this.allPass){
			//提交
			var obj = {};
			obj.userId = Job.conf.userId;
			obj.scoId = Job.conf.scoId;
			obj.courseId = Job.conf.courseId;
			obj.historyId = Job.conf.historyId;
			obj.quizCorrectNum = this.quizCorrectNum;
			obj.quizNum = this.quizNum;
			obj.allPass = this.allPass;
			obj.quizIndex = Job.conf.quizIndex;
			//Job.conf.quizScore = this.quizScore;
			Job.conf.set_quizScore(this.quizScore, this.isTest);
			obj.quizScore = this.quizScore;
			obj.quizScoreAll = Job.conf.quizScore;
			obj.isTest = this.isTest;

			$.ajax(Job.conf.quizPostUrl, {type: 'POST', data: obj}).done(function() {
				console.log('quiz post success');
			});
			
			Job.closeQuiz();
		}else{
			alert("请完成所有答题后点击'完成'");
		}
	},
	/**
	 * 查看答案
	 */
	review: function(){
		$('#job_quizlist2').html($('#job_quizlist').html());
		$('#job_quizlist2 input').each(function() {
			$(this).prop('disabled', true).parent().addClass('gray');
			if ($(this).attr('flag') == 'Y')
				$(this).parent().parent().addClass('correct');
		});
		var dl1 = $('#job_quizlist dl');
		$('#job_quizlist2 dl').each(function(n) {
			var correct = true;
			var options = $(this).find('input');
			var opts1 = dl1.eq(n).find('input');
			options.each(function(i) {
				var flag = $(this).attr('flag');
				var checked = opts1.eq(i).prop('checked');
				$(this).prop('checked', checked);
				var selected = checked ? 'Y' : 'N';
				if(flag != selected){
					correct = false;
					//return false;
				}
			});
			if(correct) {
				$(this).find('dt').append('<span class="green">【回答正确】</span>');
			} else {
				$(this).find('dt').append('<span class="red">【回答错误】</span>');
			}
		});
		Job.utils.init_radio('#job_quizlist2 :radio');
		this.setSelectedPage(2);
	}
};

Job.replay = {
	inited: false,
	init: function() {
		this.inited = true;
		$('#job_replay_btn').click(function() {
			Job.player.currentTime(0).play();
		});
		$('#job_nextvideo_btn').click(function() {
			window.nextVideo && window.nextVideo();
		});
	}
};

Job.errorTip = {
	/*
	get text() {
		return $('#job_errortip').text();
	},
	set text(s) {
		$('#job_errortip').text(s);
	},
	*/
	show: function(text, timeout) {
		timeout = timeout || 2000;
		$('#job_errortip').stop().show();
		//Job.errorTip.text = text;
		$('#job_errortip').text(text);
		Job.errorTip.resize();
		$('#job_errortip').delay(timeout).fadeOut();
	},
	//调整位置
	resize: function() {
		$('#job_errortip').css('marginLeft', -$('#job_errortip').width()/2);
	}
};
/*
 * videojs 4.7.1 fix *
 */

videojs.SeekBar.prototype.onMouseMove = Job.movePbar;
if (!window._seekup) window._seekup = videojs.SeekBar.prototype.onMouseUp;
videojs.SeekBar.prototype.onMouseUp = Job.upPbar;

//IE7/- fix
if (!window.console) window.console = {log: function(){}};
