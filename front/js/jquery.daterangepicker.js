// daterangepicker.js
// author : Chunlong Liu
// license : MIT
// www.jszen.com

(function (factory) {
		if (typeof define === 'function' && define.amd) {
			// AMD. Register as an anonymous module.
			define(['jquery', 'moment'], factory);
		} else if (typeof exports === 'object' && typeof module !== 'undefined') {
			// CommonJS. Register as a module
			module.exports = factory(require('jquery'), require('moment'));
		} else {
			// Browser globals
			factory(jQuery, moment);
		}
}(function ($, moment)
{

	$.dateRangePickerLanguages =
	{
		'default':  //default language: English
		{
			'selected': 'Selected:',
			'day':'Day',
			'days': 'Days',
			'apply': 'Close',
			'week-1' : 'mo',
			'week-2' : 'tu',
			'week-3' : 'we',
			'week-4' : 'th',
			'week-5' : 'fr',
			'week-6' : 'sa',
			'week-7' : 'su',
			'week-number': 'W',
			'month-name': ['january','february','march','april','may','june','july','august','september','october','november','december'],
			'shortcuts' : 'Shortcuts',
			'custom-values': 'Custom Values',
			'past': 'Past',
			'following':'Following',
			'previous' : 'Previous',
			'prev-week' : 'Week',
			'prev-month' : 'Month',
			'prev-year' : 'Year',
			'next':'Next',
			'next-week':'Week',
			'next-month':'Month',
			'next-year':'Year',
			'less-than' : 'Date range should not be more than %d days',
			'more-than' : 'Date range should not be less than %d days',
			'default-more' : 'Please select a date range longer than %d days',
			'default-single' : 'Please select a date',
			'default-less' : 'Please select a date range less than %d days',
			'default-range' : 'Please select a date range between %d and %d days',
			//'default-default': 'Please select a date range',
			'time':'Time',
			'hour':'Hour',
			'minute':'Minute'
		},
		
		'es':
		{
			'selected': 'Seleccionado:',
			'day':'Día',
			'days': 'Días',
			'apply': 'Cerrar',
			'week-1' : 'lu',
			'week-2' : 'ma',
			'week-3' : 'mi',
			'week-4' : 'ju',
			'week-5' : 'vi',
			'week-6' : 'sa',
			'week-7' : 'do',
			'month-name': ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'],
			'shortcuts' : 'Accesos directos',
			'past': 'Pasado',
			'following':'Siguiente',
			'previous' : 'Anterior',
			'prev-week' : 'Semana',
			'prev-month' : 'Mes',
			'prev-year' : 'Año',
			'next':'Siguiente',
			'next-week':'Semana',
			'next-month':'Mes',
			'next-year':'Año',
			'less-than' : 'El rango no debería ser mayor de %d días',
			'more-than' : 'El rango no debería ser menor de %d días',
			'default-more' : 'Por favor selecciona un rango mayor a %d días',
			'default-single' : 'Por favor selecciona un día',
			'default-less' : 'Por favor selecciona un rango menor a %d días',
			'default-range' : 'Por favor selecciona un rango entre %d y %d días',
			'default-default': 'Por favor selecciona un rango de fechas.'
		},
		
	
		
		
		
		
		
		
		
	};

	$.fn.dateRangePicker = function(opt)
	{
		if (!opt) opt = {};
		opt = $.extend(true,
		{
			autoClose: false,
			format: 'YYYY-MM-DD',
			separator: ' to ',
			language: 'auto',
			startOfWeek: 'sunday',// or monday
			getValue: function()
			{
				return $(this).val();
			},
			setValue: function(s)
			{
				if(!$(this).attr('readonly') && !$(this).is(':disabled') && s != $(this).val())
				{
					$(this).val(s);
				}
			},
			startDate: false,
			endDate: false,
			time: {
				enabled: false
			},
			minDays: 0,
			maxDays: 0,
			showShortcuts: false,
			shortcuts:
			{
				//'prev-days': [1,3,5,7],
				// 'next-days': [3,5,7],
				//'prev' : ['week','month','year'],
				// 'next' : ['week','month','year']
			},
			customShortcuts : [],
			inline:false,
			container:'body',
			alwaysOpen:false,
			singleDate:false,
			lookBehind: false,
			batchMode: false,
			duration: 200,
			stickyMonths: false,
			dayDivAttrs: [],
			dayTdAttrs: [],
			selectForward: false,
			selectBackward: false,
			applyBtnClass: '',
			singleMonth: 'auto',
			hoveringTooltip: function(days, startTime, hoveringTime)
			{
				return days > 1 ? days + ' ' + lang('days') : '';
			},
			showTopbar: true,
			swapTime: false,
			showWeekNumbers: false,
			getWeekNumber: function(date) //date will be the first day of a week
			{
				return moment(date).format('w');
			},
			customOpenAnimation: null,
			customCloseAnimation: null
		},opt);

		opt.start = false;
		opt.end = false;

		opt.startWeek = false;

		//detect a touch device
		opt.isTouchDevice = 'ontouchstart' in window || navigator.msMaxTouchPoints;

		//if it is a touch device, hide hovering tooltip
		if (opt.isTouchDevice) opt.hoveringTooltip = false;

		//show one month on mobile devices
		if (opt.singleMonth == 'auto') opt.singleMonth = $(window).width() < 480;
		if (opt.singleMonth) opt.stickyMonths = false;

		if (!opt.showTopbar) opt.autoClose = true;

		if (opt.startDate && typeof opt.startDate == 'string') opt.startDate = moment(opt.startDate,opt.format).toDate();
		if (opt.endDate && typeof opt.endDate == 'string') opt.endDate = moment(opt.endDate,opt.format).toDate();

		var langs = getLanguages();
		var box;
		var initiated = false;
		var self = this;
		var selfDom = $(self).get(0);
		var domChangeTimer;

		$(this).unbind('.datepicker').bind('click.datepicker',function(evt)
		{
			var isOpen = box.is(':visible');
			if(!isOpen) open(opt.duration);
		}).bind('change.datepicker', function(evt)
		{
			checkAndSetDefaultValue();
		}).bind('keyup.datepicker',function()
		{
			try{ clearTimeout(domChangeTimer); }catch(e){}
			domChangeTimer = setTimeout(function()
			{
				checkAndSetDefaultValue();
			},2000);
		});

		init_datepicker.call(this);

		if (opt.alwaysOpen)
		{
			open(0);
		}

		// expose some api
		$(this).data('dateRangePicker',
		{
			setDateRange : function(d1,d2,silent)
			{
				if (typeof d1 == 'string' && typeof d2 == 'string')
				{
					d1 = moment(d1,opt.format).toDate();
					d2 = moment(d2,opt.format).toDate();
				}
				setDateRange(d1,d2,silent);
			},
			clear: clearSelection,
			close: closeDatePicker,
			open: open,
			redraw: redrawDatePicker,
			getDatePicker: getDatePicker,
			resetMonthsView: resetMonthsView,
			destroy: function()
			{
				$(self).unbind('.datepicker');
				$(self).data('dateRangePicker','');
				$(self).data('date-picker-opened',null);
				box.remove();
				$(window).unbind('resize.datepicker',calcPosition);
				$(document).unbind('click.datepicker',closeDatePicker);
			}
		});

		$(window).bind('resize.datepicker',calcPosition);

		return this;

		function IsOwnDatePickerClicked(evt, selfObj)
		{
			return ( selfObj.contains(evt.target) || evt.target == selfObj  || (selfObj.childNodes != undefined && $.inArray(evt.target, selfObj.childNodes)>=0));
		}

		function init_datepicker()
		{
			var self = this;

			if ($(this).data('date-picker-opened'))
			{
				closeDatePicker();
				return;
			}
			$(this).data('date-picker-opened',true);


			box = createDom().hide();
			box.append('<div class="date-range-length-tip"></div>');
			box.delegate('.day', 'mouseleave', function()
			{
				box.find('.date-range-length-tip').hide();
				if (opt.singleDate) {
				    clearHovering();
				}
			});

			$(opt.container).append(box);

			if (!opt.inline)
			{
				calcPosition();
			}
			else
			{
				box.addClass('inline-wrapper');
			}

			if (opt.alwaysOpen)
			{
				box.find('.apply-btn').hide();
			}

			var defaultTime = getDefaultTime();
			resetMonthsView(defaultTime);

			if (opt.time.enabled)
			{
				if ((opt.startDate && opt.endDate) || (opt.start && opt.end)) {
					showTime(moment(opt.start || opt.startDate).toDate(),'time1');
					showTime(moment(opt.end || opt.endDate).toDate(),'time2');
				} else {
					var defaultEndTime = opt.defaultEndTime ? opt.defaultEndTime : defaultTime;
					showTime(defaultTime,'time1');
					showTime(defaultEndTime,'time2');
				}
			}

			//showSelectedInfo();


			var defaultTopText = '';
			if (opt.singleDate)
				defaultTopText = lang('default-single');
			else if (opt.minDays && opt.maxDays)
				defaultTopText = lang('default-range');
			else if (opt.minDays)
				defaultTopText = lang('default-more');
			else if (opt.maxDays)
				defaultTopText = lang('default-less');
			else
				defaultTopText = lang('default-default');

			box.find('.default-top').html( defaultTopText.replace(/\%d/,opt.minDays).replace(/\%d/,opt.maxDays));
			if (opt.singleMonth)
			{
				box.addClass('single-month');
			}
			else
			{
				box.addClass('two-months');
			}


			setTimeout(function()
			{
				updateCalendarWidth();
				initiated = true;
			},0);

			box.click(function(evt)
			{
				evt.stopPropagation();
			});

			//if user click other place of the webpage, close date range picker window
			$(document).bind('click.datepicker',function(evt)
			{
				if (!IsOwnDatePickerClicked(evt, self[0])) {
					if (box.is(':visible')) closeDatePicker();
				}
			});

			box.find('.next').click(function()
			{
				if(!opt.stickyMonths)
					gotoNextMonth(this);
				else
					gotoNextMonth_stickily(this);
			});

			function gotoNextMonth(self)
			{
				var isMonth2 = $(self).parents('table').hasClass('month2');
				var month = isMonth2 ? opt.month2 : opt.month1;
				month = nextMonth(month);
				if (!opt.singleMonth && !opt.singleDate && !isMonth2 && compare_month(month,opt.month2) >= 0 || isMonthOutOfBounds(month)) return;
				showMonth(month,isMonth2 ? 'month2' : 'month1');
				showGap();
			}

			function gotoNextMonth_stickily(self) {
				var nextMonth1 = nextMonth(opt.month1);
				var nextMonth2 = nextMonth(opt.month2);
				if(isMonthOutOfBounds(nextMonth2)) return;
				if (!opt.singleDate && compare_month(nextMonth1,nextMonth2) >= 0) return;
				showMonth(nextMonth1, 'month1');
				showMonth(nextMonth2, 'month2');
				showSelectedDays();
			}


			box.find('.prev').click(function()
			{
				if(!opt.stickyMonths)
					gotoPrevMonth(this);
				else
					gotoPrevMonth_stickily(this);
			});

			function gotoPrevMonth(self) {
				var isMonth2 = $(self).parents('table').hasClass('month2');
				var month = isMonth2 ? opt.month2 : opt.month1;
				month = prevMonth(month);
				if (isMonth2 && compare_month(month,opt.month1) <= 0 || isMonthOutOfBounds(month)) return;
				showMonth(month,isMonth2 ? 'month2' : 'month1');
				showGap();
			}

			function gotoPrevMonth_stickily(self)
			{
				var prevMonth1 = prevMonth(opt.month1);
				var prevMonth2 = prevMonth(opt.month2);
				if(isMonthOutOfBounds(prevMonth1)) return;
				if(!opt.singleDate && compare_month(prevMonth2,prevMonth1) <= 0) return;
				showMonth(prevMonth2, 'month2');
				showMonth(prevMonth1, 'month1');
				showSelectedDays();
			}

			box.delegate('.day','click', function(evt)
			{
				dayClicked($(this));
			});

			box.delegate('.day','mouseenter',function(evt)
			{
				dayHovering($(this));
			});

			box.delegate('.week-number', 'click', function(evt)
			{
				weekNumberClicked($(this));
			});

			box.attr('unselectable', 'on')
			.css('user-select', 'none')
			.bind('selectstart', function(e)
			{
				e.preventDefault(); return false;
			});

			box.find('.apply-btn').click(function()
			{
				closeDatePicker();
				var dateRange = getDateString(new Date(opt.start))+ opt.separator +getDateString(new Date(opt.end));
				$(self).trigger('datepicker-apply',
				{
					'value': dateRange,
					'date1' : new Date(opt.start),
					'date2' : new Date(opt.end)
				});
			});

			box.find('[custom]').click(function()
			{
				var valueName = $(this).attr('custom');
				opt.start = false;
				opt.end = false;
				box.find('.day.checked').removeClass('checked');
				opt.setValue.call(selfDom, valueName);
				checkSelectionValid();
				showSelectedInfo(true);
				showSelectedDays();
				if (opt.autoClose) closeDatePicker();
			});

			box.find('[shortcut]').click(function()
			{
				var shortcut = $(this).attr('shortcut');
				var end = new Date(),start = false;
				if (shortcut.indexOf('day') != -1)
				{
					var day = parseInt(shortcut.split(',',2)[1],10);
					start = new Date(new Date().getTime() + 86400000*day);
					end = new Date(end.getTime() + 86400000*(day>0?1:-1) );
				}
				else if (shortcut.indexOf('week')!= -1)
				{
					var dir = shortcut.indexOf('prev,') != -1 ? -1 : 1;

					if (dir == 1)
						var stopDay = opt.startOfWeek == 'monday' ? 1 : 0;
					else
						var stopDay = opt.startOfWeek == 'monday' ? 0 : 6;

					end = new Date(end.getTime() - 86400000);
					while(end.getDay() != stopDay) end = new Date(end.getTime() + dir*86400000);
					start = new Date(end.getTime() + dir*86400000*6);
				}
				else if (shortcut.indexOf('month') != -1)
				{
					var dir = shortcut.indexOf('prev,') != -1 ? -1 : 1;
					if (dir == 1)
						start = nextMonth(end);
					else
						start = prevMonth(end);
					start.setDate(1);
					end = nextMonth(start);
					end.setDate(1);
					end = new Date(end.getTime() - 86400000);
				}
				else if (shortcut.indexOf('year') != -1)
				{
					var dir = shortcut.indexOf('prev,') != -1 ? -1 : 1;
					start = new Date();
					start.setFullYear(end.getFullYear() + dir);
					start.setMonth(0);
					start.setDate(1);
					end.setFullYear(end.getFullYear() + dir);
					end.setMonth(11);
					end.setDate(31);
				}
				else if (shortcut == 'custom')
				{
					var name = $(this).html();
					if (opt.customShortcuts && opt.customShortcuts.length > 0)
					{
						for(var i=0;i<opt.customShortcuts.length;i++)
						{
							var sh = opt.customShortcuts[i];
							if (sh.name == name)
							{
								var data = [];
								// try
								// {
									data = sh['dates'].call();
								//}catch(e){}
								if (data && data.length == 2)
								{
									start = data[0];
									end = data[1];
								}

								// if only one date is specified then just move calendars there
								// move calendars to show this date's month and next months
								if (data && data.length == 1)
								{
									movetodate = data[0];
									showMonth(movetodate,'month1');
									showMonth(nextMonth(movetodate),'month2');
									showGap();
								}

								break;
							}
						}
					}
				}
				if (start && end)
				{
					setDateRange(start,end);
					checkSelectionValid();
				}
			});

			box.find('.time1 input[type=range]').bind('change touchmove mousemove', function (e) {
				var target = e.target,
					hour = target.name == 'hour' ? $(target).val().replace(/^(\d{1})$/, '0$1') : undefined,
					min = target.name == 'minute' ? $(target).val().replace(/^(\d{1})$/, '0$1') : undefined;
				setTime('time1', hour, min);
			});

			box.find('.time2 input[type=range]').bind('change touchmove mousemove', function (e) {
				var target = e.target,
					hour = target.name == 'hour' ? $(target).val().replace(/^(\d{1})$/, '0$1') : undefined,
					min = target.name == 'minute' ? $(target).val().replace(/^(\d{1})$/, '0$1') : undefined;
				setTime('time2', hour, min);
			});

		}


		function calcPosition()
		{
			if (!opt.inline)
			{
				var offset = $(self).offset();
				if ($(opt.container).css('position') == 'relative')
				{
					var containerOffset = $(opt.container).offset();
					box.css(
					{
						top: offset.top - containerOffset.top + $(self).outerHeight() + 4,
						left: offset.left - containerOffset.left
					});
				}
				else
				{
					if (offset.left < 460) //left to right
					{
						box.css(
						{
							top: offset.top+$(self).outerHeight() + parseInt($('body').css('border-top') || 0,10 ),
							left: offset.left
						});
					}
					else
					{
						box.css(
						{
							top: offset.top+$(self).outerHeight() + parseInt($('body').css('border-top') || 0,10 ),
							left: offset.left + $(self).width() - box.width() - 16
						});
					}
				}
			}
		}

		// Return the date picker wrapper element
		function getDatePicker()
		{
			return box;
		}

		function open(animationTime)
		{
			console.log(3434);
			calcPosition();
			redrawDatePicker();
			checkAndSetDefaultValue();
			if (opt.customOpenAnimation)
			{
				opt.customOpenAnimation.call(box.get(0), function()
				{
					$(self).trigger('datepicker-opened', {relatedTarget: box});
				});
			}
			else
			{
				box.slideDown(animationTime, function(){
					$(self).trigger('datepicker-opened', {relatedTarget: box});
				});
			}
			$(self).trigger('datepicker-open', {relatedTarget: box});
			showGap();
			updateCalendarWidth();
		}

		function checkAndSetDefaultValue()
		{
			var __default_string = opt.getValue.call(selfDom);
			var defaults = __default_string ? __default_string.split( opt.separator ) : '';

			if (defaults && ((defaults.length==1 && opt.singleDate) || defaults.length>=2))
			{
				var ___format = opt.format;
				if (___format.match(/Do/))
				{

					___format = ___format.replace(/Do/,'D');
					defaults[0] = defaults[0].replace(/(\d+)(th|nd|st)/,'$1');
					if(defaults.length >= 2){
						defaults[1] = defaults[1].replace(/(\d+)(th|nd|st)/,'$1');
					}
				}
				// set initiated  to avoid triggerring datepicker-change event
				initiated = false;
				if(defaults.length >= 2){
					setDateRange(getValidValue(defaults[0], ___format, moment.locale(opt.language)),getValidValue(defaults[1], ___format, moment.locale(opt.language)));
				}
				else if(defaults.length==1 && opt.singleDate){
					setSingleDate(getValidValue(defaults[0], ___format, moment.locale(opt.language)));
				}

				initiated = true;
			}
		}

		function getValidValue(date, format, locale) {
			if (moment(date, format, locale).isValid()) {
				return moment(date, format, locale).toDate();
			} else {
				return moment().toDate();
			}
		}

		function updateCalendarWidth()
		{
			var gapMargin = box.find('.gap').css('margin-left');
			if (gapMargin) gapMargin = parseInt(gapMargin);
			var w1 = box.find('.month1').width();
			var w2 = box.find('.gap').width() + ( gapMargin ? gapMargin*2 : 0 );
			var w3 = box.find('.month2').width();
			box.find('.month-wrapper table-responsive col-lg-12').width(w1 + w2 + w3);
		}

		function renderTime (name, date) {
			box.find('.' + name + ' input[type=range].hour-range').val(moment(date).hours());
			box.find('.' + name + ' input[type=range].minute-range').val(moment(date).minutes());
			setTime(name, moment(date).format('HH'), moment(date).format('mm'));
		}

		function changeTime (name, date) {
			opt[name] = parseInt(
				moment(parseInt(date))
					.startOf('day')
					.add(moment(opt[name + 'Time']).format('HH'), 'h')
					.add(moment(opt[name + 'Time']).format('mm'), 'm').valueOf()
				);
		}

		function swapTime () {
			renderTime('time1', opt.start);
			renderTime('time2', opt.end);
		}

		function setTime (name, hour, minute)
		{
			hour && (box.find('.' + name + ' .hour-val').text(hour));
			minute && (box.find('.' + name + ' .minute-val').text(minute));
			switch (name) {
				case 'time1':
					if (opt.start) {
						setRange('start', moment(opt.start));
					}
					setRange('startTime', moment(opt.startTime || moment().valueOf()));
					break;
				case 'time2':
					if (opt.end) {
						setRange('end', moment(opt.end));
					}
					setRange('endTime', moment(opt.endTime || moment().valueOf()));
					break;
			}
			function setRange(name, timePoint) {
				var h = timePoint.format('HH'),
					m = timePoint.format('mm');
				opt[name] = timePoint
					.startOf('day')
					.add(hour || h, 'h')
					.add(minute || m, 'm')
					.valueOf();
			}
			checkSelectionValid();
			showSelectedInfo();
			showSelectedDays();
		}

		function clearSelection()
		{
			opt.start = false;
			opt.end = false;
			box.find('.day.checked').removeClass('checked');
			box.find('.day.last-date-selected').removeClass('last-date-selected');
			box.find('.day.first-date-selected').removeClass('first-date-selected');
			opt.setValue.call(selfDom, '');
			checkSelectionValid();
			showSelectedInfo();
			showSelectedDays();
		}

		function handleStart(time)
		{
			var r = time;
			if  (opt.batchMode === 'week-range')
			{
				if (opt.startOfWeek === 'monday')
				{
					r = moment(parseInt(time)).startOf('isoweek').valueOf();
				}
				else
				{
					r = moment(parseInt(time)).startOf('week').valueOf();
				}
			}
			else if (opt.batchMode === 'month-range')
			{
				r = moment(parseInt(time)).startOf('month').valueOf();
			}
			return r;
		}

		function handleEnd(time)
		{
			var r = time;
			if  (opt.batchMode === 'week-range')
			{
				if (opt.startOfWeek === 'monday')
				{
					r = moment(parseInt(time)).endOf('isoweek').valueOf();
				}
				else
				{
					r = moment(parseInt(time)).endOf('week').valueOf();
				}
			}
			else if (opt.batchMode === 'month-range')
			{
				r = moment(parseInt(time)).endOf('month').valueOf();
			}
			return r;
		}


		function dayClicked(day)
		{
			if (day.hasClass('invalid')) return;
			var time = day.attr('time');
			day.addClass('checked');
			if ( opt.singleDate )
			{
				opt.start = time;
				opt.end = false;
			}
			else if  (opt.batchMode === 'week')
			{
				if (opt.startOfWeek === 'monday') {
					opt.start = moment(parseInt(time)).startOf('isoweek').valueOf();
					opt.end = moment(parseInt(time)).endOf('isoweek').valueOf();
				} else {
					opt.end = moment(parseInt(time)).endOf('week').valueOf();
					opt.start = moment(parseInt(time)).startOf('week').valueOf();
				}
			}
			else if  (opt.batchMode === 'workweek')
			{
				opt.start = moment(parseInt(time)).day(1).valueOf();
				opt.end = moment(parseInt(time)).day(5).valueOf();
			}
			else if  (opt.batchMode === 'weekend')
			{
				opt.start = moment(parseInt(time)).day(6).valueOf();
				opt.end = moment(parseInt(time)).day(7).valueOf();
			}
			else if (opt.batchMode === 'month')
			{
				opt.start = moment(parseInt(time)).startOf('month').valueOf();
				opt.end = moment(parseInt(time)).endOf('month').valueOf();
			}
			else if ((opt.start && opt.end) || (!opt.start && !opt.end) )
			{
				opt.start = handleStart(time);
				opt.end = false;
			}
			else if (opt.start)
			{
				opt.end = handleEnd(time);
				if (opt.time.enabled) {
					changeTime('end', opt.end);
				}
			}

			//Update time in case it is enabled and timestamps are available
			if(opt.time.enabled) {
				if(opt.start) {
					changeTime('start', opt.start);
				}
				if(opt.end) {
					changeTime('end', opt.end);
				}
			}

			//In case the start is after the end, swap the timestamps
			if (!opt.singleDate && opt.start && opt.end && opt.start > opt.end)
			{
				var tmp = opt.end;
				opt.end = handleEnd(opt.start);
				opt.start = handleStart(tmp);
				if (opt.time.enabled && opt.swapTime) {
					swapTime();
				}
			}

			opt.start = parseInt(opt.start);
			opt.end = parseInt(opt.end);

			clearHovering();
			if (opt.start && !opt.end)
			{
				$(self).trigger('datepicker-first-date-selected',
				{
					'date1' : new Date(opt.start)
				});
				dayHovering(day);
			}
			updateSelectableRange(time);

			checkSelectionValid();
			showSelectedInfo();
			showSelectedDays();
			autoclose();
		}


		function weekNumberClicked(weekNumberDom)
		{
			var thisTime = parseInt(weekNumberDom.attr('data-start-time'),10);
			if (!opt.startWeek)
			{
				opt.startWeek = thisTime;
				weekNumberDom.addClass('week-number-selected');
				var date1 = new Date(thisTime);
				opt.start = moment(date1).day(opt.startOfWeek == 'monday' ? 1 : 0).valueOf();
				opt.end = moment(date1).day(opt.startOfWeek == 'monday' ? 7 : 6).valueOf();
			}
			else
			{
				box.find('.week-number-selected').removeClass('week-number-selected');
				var date1 = new Date(thisTime < opt.startWeek ? thisTime : opt.startWeek);
				var date2 = new Date(thisTime < opt.startWeek ? opt.startWeek : thisTime);
				opt.startWeek = false;
				opt.start = moment(date1).day(opt.startOfWeek == 'monday' ? 1 : 0).valueOf();
				opt.end = moment(date2).day(opt.startOfWeek == 'monday' ? 7 : 6).valueOf();
			}
			updateSelectableRange();
			checkSelectionValid();
			showSelectedInfo();
			showSelectedDays();
			autoclose();
		}

		function isValidTime(time)
		{
			time = parseInt(time, 10);
			if (opt.startDate && compare_day(time, opt.startDate) < 0) return false;
			if (opt.endDate && compare_day(time, opt.endDate) > 0) return false;

			if (opt.start && !opt.end && !opt.singleDate)
			{
				//check maxDays and minDays setting
				if (opt.maxDays > 0 && countDays(time, opt.start) > opt.maxDays) return false;
				if (opt.minDays > 0 && countDays(time, opt.start) < opt.minDays) return false;

				//check selectForward and selectBackward
				if (opt.selectForward && time < opt.start ) return false;
				if (opt.selectBackward && time > opt.start) return false;

				//check disabled days
				if (opt.beforeShowDay && typeof opt.beforeShowDay == 'function')
				{
					var valid = true;
					var timeTmp = time;
					while( countDays(timeTmp, opt.start) > 1 )
					{
						var arr = opt.beforeShowDay( new Date(timeTmp) );
						if (!arr[0])
						{
							valid = false;
							break;
						}
						if (Math.abs(timeTmp - opt.start) < 86400000) break;
						if (timeTmp > opt.start) timeTmp -= 86400000;
						if (timeTmp < opt.start) timeTmp += 86400000;
					}
					if (!valid) return false;
				}
			}
			return true;
		}


		function updateSelectableRange()
		{
			box.find('.day.invalid.tmp').removeClass('tmp invalid').addClass('valid');
			if (opt.start && !opt.end)
			{
				box.find('.day.toMonth.valid').each(function()
				{
					var time = parseInt($(this).attr('time'), 10);
					if (!isValidTime(time))
						$(this).addClass('invalid tmp').removeClass('valid');
					else
						$(this).addClass('valid tmp').removeClass('invalid');
				});
			}

			return true;
		}


		function dayHovering(day)
		{
			var hoverTime = parseInt(day.attr('time'));
			var tooltip = '';

			if (day.hasClass('has-tooltip') && day.attr('data-tooltip'))
			{
				tooltip = '<span style="white-space:nowrap">'+day.attr('data-tooltip')+'</span>';
			}
			else if (!day.hasClass('invalid'))
			{
				if (opt.singleDate)
				{
					box.find('.day.hovering').removeClass('hovering');
					day.addClass('hovering');
				}
				else
				{
					box.find('.day').each(function()
					{
						var time = parseInt($(this).attr('time')),
							start = opt.start,
							end = opt.end;

						if ( time == hoverTime )
						{
							$(this).addClass('hovering');
						}
						else
						{
							$(this).removeClass('hovering');
						}

						if (
							( opt.start && !opt.end ) &&
							(
								( opt.start < time && hoverTime >= time ) ||
								( opt.start > time && hoverTime <= time )
							)
						)
						{
							$(this).addClass('hovering');
						}
						else
						{
							$(this).removeClass('hovering');
						}
					});

					if (opt.start && !opt.end)
					{
						var days = countDays(hoverTime, opt.start);
						if (opt.hoveringTooltip)
						{
							if (typeof opt.hoveringTooltip == 'function')
							{
								tooltip = opt.hoveringTooltip(days, opt.start, hoverTime);
							}
							else if (opt.hoveringTooltip === true && days > 1)
							{
								tooltip = days + ' ' + lang('days');
							}
						}
					}
				}
			}

			if (tooltip)
			{
				var posDay = day.offset();
				var posBox = box.offset();

				var _left = posDay.left - posBox.left;
				var _top = posDay.top - posBox.top;
				_left += day.width()/2;


				var $tip = box.find('.date-range-length-tip');
				var w = $tip.css({'visibility':'hidden', 'display':'none'}).html(tooltip).width();
				var h = $tip.height();
				_left -= w/2;
				_top -= h;
				setTimeout(function()
				{
					$tip.css({left:_left, top:_top, display:'block','visibility':'visible'});
				},10);
			}
			else
			{
				box.find('.date-range-length-tip').hide();
			}
		}

		function clearHovering()
		{
			box.find('.day.hovering').removeClass('hovering');
			box.find('.date-range-length-tip').hide();
		}

		function autoclose () {
			if (opt.singleDate === true) {
				if (initiated && opt.start )
				{
					if (opt.autoClose) closeDatePicker();
				}
			} else {
				if (initiated && opt.start && opt.end)
				{
					if (opt.autoClose) closeDatePicker();
				}
			}
		}

		function checkSelectionValid()
		{
			var days = Math.ceil( (opt.end - opt.start) / 86400000 ) + 1;
			if (opt.singleDate) { // Validate if only start is there
				if (opt.start && !opt.end)
					box.find('.drp_top-bar').removeClass('error').addClass('normal');
				else
					box.find('.drp_top-bar').removeClass('error').removeClass('normal');
			}
			else if ( opt.maxDays && days > opt.maxDays)
			{
				opt.start = false;
				opt.end = false;
				box.find('.day').removeClass('checked');
				box.find('.drp_top-bar').removeClass('normal').addClass('error').find('.error-top').html( lang('less-than').replace('%d',opt.maxDays) );
			}
			else if ( opt.minDays && days < opt.minDays)
			{
				opt.start = false;
				opt.end = false;
				box.find('.day').removeClass('checked');
				box.find('.drp_top-bar').removeClass('normal').addClass('error').find('.error-top').html( lang('more-than').replace('%d',opt.minDays) );
			}
			else
			{
				if (opt.start || opt.end)
					box.find('.drp_top-bar').removeClass('error').addClass('normal');
				else
					box.find('.drp_top-bar').removeClass('error').removeClass('normal');
			}

			if ( (opt.singleDate && opt.start && !opt.end) || (!opt.singleDate && opt.start && opt.end) )
			{
				box.find('.apply-btn').removeClass('disabled');
			}
			else
			{
				box.find('.apply-btn').addClass('disabled');
			}

			if (opt.batchMode)
			{
				if (
					(opt.start && opt.startDate && compare_day(opt.start, opt.startDate) < 0) ||
					(opt.end && opt.endDate && compare_day(opt.end, opt.endDate) > 0)
				) {
					opt.start = false;
					opt.end = false;
					box.find('.day').removeClass('checked');
				}
			}
		}

		function showSelectedInfo(forceValid,silent)
		{
			box.find('.start-day').html('...');
			box.find('.end-day').html('...');
			box.find('.selected-days').hide();
			if (opt.start)
			{
				box.find('.start-day').html(getDateString(new Date(parseInt(opt.start))));
			}
			if (opt.end)
			{
				box.find('.end-day').html(getDateString(new Date(parseInt(opt.end))));
			}

			if (opt.start && opt.singleDate)
			{
				box.find('.apply-btn').removeClass('disabled');
				var dateRange = getDateString(new Date(opt.start));
				opt.setValue.call(selfDom, dateRange, getDateString(new Date(opt.start)), getDateString(new Date(opt.end)));

				if (initiated && !silent)
				{
					$(self).trigger('datepicker-change',
					{
						'value': dateRange,
						'date1' : new Date(opt.start)
					});
				}
			}
			else if (opt.start && opt.end)
			{
				box.find('.selected-days').show().find('.selected-days-num').html(countDays(opt.end, opt.start));
				box.find('.apply-btn').removeClass('disabled');
				var dateRange = getDateString(new Date(opt.start))+ opt.separator +getDateString(new Date(opt.end));
				opt.setValue.call(selfDom,dateRange, getDateString(new Date(opt.start)), getDateString(new Date(opt.end)));
				if (initiated && !silent)
				{
					$(self).trigger('datepicker-change',
					{
						'value': dateRange,
						'date1' : new Date(opt.start),
						'date2' : new Date(opt.end)
					});
				}
			}
			else if (forceValid)
			{
				box.find('.apply-btn').removeClass('disabled');
			}
			else
			{
				box.find('.apply-btn').addClass('disabled');
			}
		}

		function countDays(start,end)
		{
			return Math.abs( daysFrom1970(start) - daysFrom1970(end) ) + 1;
		}

		function setDateRange(date1,date2,silent)
		{
			if (date1.getTime() > date2.getTime())
			{
				var tmp = date2;
				date2 = date1;
				date1 = tmp;
				tmp = null;
			}
			var valid = true;
			if (opt.startDate && compare_day(date1,opt.startDate) < 0) valid = false;
			if (opt.endDate && compare_day(date2,opt.endDate) > 0) valid = false;
			if (!valid)
			{
				showMonth(opt.startDate,'month1');
				showMonth(nextMonth(opt.startDate),'month2');
				showGap();
				return;
			}

			opt.start = date1.getTime();
			opt.end = date2.getTime();

			if (opt.time.enabled)
			{
				renderTime('time1', date1);
				renderTime('time2', date2);
			}

			if (opt.stickyMonths || (compare_day(date1,date2) > 0 && compare_month(date1,date2) === 0))
			{
				if (opt.lookBehind) {
					date1 = prevMonth(date2);
				} else {
					date2 = nextMonth(date1);
				}
			}

			if(opt.stickyMonths && compare_month(date2,opt.endDate) > 0) {
				date1 = prevMonth(date1);
				date2 = prevMonth(date2);
			}

			if (!opt.stickyMonths) {
				if (compare_month(date1,date2) === 0)
				{
					if (opt.lookBehind) {
						date1 = prevMonth(date2);
					} else {
						date2 = nextMonth(date1);
					}
				}
			}

			showMonth(date1,'month1');
			showMonth(date2,'month2');
			showGap();
			checkSelectionValid();
			showSelectedInfo(false,silent);
			autoclose();
		}

		function setSingleDate(date1)
		{

			var valid = true;
			if (opt.startDate && compare_day(date1,opt.startDate) < 0) valid = false;
			if (opt.endDate && compare_day(date1,opt.endDate) > 0) valid = false;
			if (!valid)
			{
				showMonth(opt.startDate,'month1');
				return;
			}

			opt.start = date1.getTime();


			if (opt.time.enabled) {
				renderTime('time1', date1);

			}
			showMonth(date1,'month1');
			if (opt.singleMonth !== true) {
      				date2 = nextMonth(date1);
      				showMonth(date2, 'month2');
    			}
			showGap();
			showSelectedInfo();
			autoclose();
		}

		function showSelectedDays()
		{
			if (!opt.start && !opt.end) return;
			box.find('.day').each(function()
			{
				var time = parseInt($(this).attr('time')),
					start = opt.start,
					end = opt.end;
				if (opt.time.enabled) {
					time = moment(time).startOf('day').valueOf();
					start = moment(start || moment().valueOf()).startOf('day').valueOf();
					end = moment(end || moment().valueOf()).startOf('day').valueOf();
				}
				if (
					(opt.start && opt.end && end >= time && start <= time ) ||
					( opt.start && !opt.end && moment(start).format('YYYY-MM-DD') == moment(time).format('YYYY-MM-DD') )
				)
				{
					$(this).addClass('checked');
				}
				else
				{
					$(this).removeClass('checked');
				}

				//add first-date-selected class name to the first date selected
				if ( opt.start && moment(start).format('YYYY-MM-DD') == moment(time).format('YYYY-MM-DD') )
				{
					$(this).addClass('first-date-selected');
				}
				else
				{
					$(this).removeClass('first-date-selected');
				}
				//add last-date-selected
				if ( opt.end && moment(end).format('YYYY-MM-DD') == moment(time).format('YYYY-MM-DD') )
				{
					$(this).addClass('last-date-selected');
				}
				else
				{
					$(this).removeClass('last-date-selected');
				}
			});

			box.find('.week-number').each(function()
			{
				if ($(this).attr('data-start-time') == opt.startWeek)
				{
					$(this).addClass('week-number-selected');
				}
			});
		}

		function showMonth(date,month)
		{
			date = moment(date).toDate();
			var monthName = nameMonth(date.getMonth());
			box.find('.'+month+' .month-name').html(monthName+' '+date.getFullYear());
			box.find('.'+month+' tbody').html(createMonthHTML(date));
			opt[month] = date;
			updateSelectableRange();
		}

		function showTime(date,name)
		{
			box.find('.' + name).append(getTimeHTML());
			renderTime(name, date);
		}

		function nameMonth(m)
		{
			return lang('month-name')[m];
		}

		function getDateString(d)
		{
			return moment(d).format(opt.format);
		}

		function showGap()
		{
			showSelectedDays();
			var m1 = parseInt(moment(opt.month1).format('YYYYMM'));
			var m2 = parseInt(moment(opt.month2).format('YYYYMM'));
			var p = Math.abs(m1 - m2);
			var shouldShow = (p > 1 && p !=89);
			if (shouldShow)
			{
				box.addClass('has-gap').removeClass('no-gap').find('.gap').css('visibility','visible');
			}
			else
			{
				box.removeClass('has-gap').addClass('no-gap').find('.gap').css('visibility','hidden');
			}
			var h1 = box.find('table.month1').height();
			var h2 = box.find('table.month2').height();
			box.find('.gap').height(Math.max(h1,h2)+10);
		}

		function closeDatePicker()
		{
			if (opt.alwaysOpen) return;

			var afterAnim = function()
			{
				$(self).data('date-picker-opened',false);
				$(self).trigger('datepicker-closed', {relatedTarget: box});
			};
			if (opt.customCloseAnimation)
			{
				opt.customCloseAnimation.call(box.get(0), afterAnim);
			}
			else
			{
				$(box).slideUp(opt.duration, afterAnim);
			}
			$(self).trigger('datepicker-close', {relatedTarget: box});
		}

		function redrawDatePicker()
		{
			showMonth(opt.month1, 'month1');
			showMonth(opt.month2, 'month2');
		}

		function compare_month(m1,m2)
		{
			var p = parseInt(moment(m1).format('YYYYMM')) - parseInt(moment(m2).format('YYYYMM'));
			if (p > 0 ) return 1;
			if (p === 0) return 0;
			return -1;
		}

		function compare_day(m1,m2)
		{
			var p = parseInt(moment(m1).format('YYYYMMDD')) - parseInt(moment(m2).format('YYYYMMDD'));
			if (p > 0 ) return 1;
			if (p === 0) return 0;
			return -1;
		}

		function nextMonth(month)
		{
			return moment(month).add(1, 'months').toDate();
		}

		function prevMonth(month)
		{
			return moment(month).add(-1, 'months').toDate();
		}

		function getTimeHTML()
		{
			return '<div>' +
					'<span>'+lang('Time')+': <span class="hour-val">00</span>:<span class="minute-val">00</span></span>' +
				'</div>' +
				'<div class="hour">' +
					'<label>'+lang('Hour')+': <input type="range" class="hour-range" name="hour" min="0" max="23"></label>' +
				'</div>' +
				'<div class="minute">' +
					'<label>'+lang('Minute')+': <input type="range" class="minute-range" name="minute" min="0" max="59"></label>' +
				'</div>';
		}

		function createDom()
		{
			var html = '<div class="date-picker-wrapper';
			if ( opt.extraClass ) html += ' '+opt.extraClass+' ';
			if ( opt.singleDate ) html += ' single-date ';
			if ( !opt.showShortcuts ) html += ' no-shortcuts ';
			if ( !opt.showTopbar ) html += ' no-topbar ';
			if ( opt.customTopBar) html += ' custom-topbar ';
			html += '">';
/*
			if (opt.showTopbar)
			{
				html += '<div class="drp_top-bar">';

				if (opt.customTopBar)
				{
					if (typeof opt.customTopBar == 'function') opt.customTopBar = opt.customTopBar();
					html += '<div class="custom-top">'+opt.customTopBar+'</div>';
				}
				else
				{
					html += '<div class="normal-top">' +
							'<span style="color:#333">'+lang('selected')+' </span> <b class="start-day">...</b>';
					if ( ! opt.singleDate ) {
						html += ' <span class="separator-day">'+opt.separator+'</span> <b class="end-day">...</b> <i class="selected-days">(<span class="selected-days-num">3</span> '+lang('days')+')</i>';
					}
					html += '</div>';
					html += '<div class="error-top">error</div>' +
						'<div class="default-top">default</div>';
				}

				//html += '<input type="button" class="apply-btn disabled'+ getApplyBtnClass() +'" value="'+lang('apply')+'" />';
				html += '</div>';
			}*/

			var _colspan = opt.showWeekNumbers ? 6 : 5;
			html += '<div class="month-wrapper">' + '<div class="arrow-t"></div>' +
				'<table class="month1" cellspacing="0" border="0" cellpadding="0"><thead><tr class="caption"><th style="width:27px;"><span class="prev">&lt;</span></th><th colspan="'+_colspan+'" class="month-name"></th><th style="width:27px;">' + (opt.singleDate || !opt.stickyMonths ? '<span class="next">&gt;</span>': '') + '</th></tr><tr class="week-name">'+getWeekHead()+'</thead><tbody></tbody></table>';

			if ( hasMonth2() )
			{
				html += '<div class="gap">'+getGapHTML()+'</div>' +
					'<table class="month2" cellspacing="0" border="0" cellpadding="0"><thead><tr class="caption"><th style="width:27px;">' + (!opt.stickyMonths ? '<span class="prev">&lt;</span>': '') + '</th><th colspan="'+_colspan+'" class="month-name"></th><th style="width:27px;"><span class="next">&gt;</span></th></tr><tr class="week-name">'+getWeekHead()+'</thead><tbody></tbody></table>';
			}
				//+'</div>'
			html +=	'<div style="clear:both;height:0;font-size:0;"></div>' +
				'<div class="time">' +
				'<div class="time1"></div>';
			if ( ! opt.singleDate ) {
				html += '<div class="time2"></div>';
			}
			html += '</div>' +
				'<div style="clear:both;height:0;font-size:0;"></div>' +
				'</div>';

			html += '<div class="footer">';
			if (opt.showShortcuts)
			{
				html += '<div class="shortcuts"><b>'+lang('shortcuts')+'</b>';

				var data = opt.shortcuts;
				if (data)
				{
					if (data['prev-days'] && data['prev-days'].length > 0)
					{
						html += '&nbsp;<span class="prev-days">'+lang('past');
						for(var i=0;i<data['prev-days'].length; i++)
						{
							var name = data['prev-days'][i];
							name += (data['prev-days'][i] > 1) ? lang('days') : lang('day');
							html += ' <a href="javascript:;" shortcut="day,-'+data['prev-days'][i]+'">'+name+'</a>';
						}
						html+='</span>';
					}

					if (data['next-days'] && data['next-days'].length > 0)
					{
						html += '&nbsp;<span class="next-days">'+lang('following');
						for(var i=0;i<data['next-days'].length; i++)
						{
							var name = data['next-days'][i];
							name += (data['next-days'][i] > 1) ? lang('days') : lang('day');
							html += ' <a href="javascript:;" shortcut="day,'+data['next-days'][i]+'">'+name+'</a>';
						}
						html+= '</span>';
					}

					if (data.prev && data.prev.length > 0)
					{
						html += '&nbsp;<span class="prev-buttons">'+lang('previous');
						for(var i=0;i<data.prev.length; i++)
						{
							var name = lang('prev-'+data.prev[i]);
							html += ' <a href="javascript:;" shortcut="prev,'+data.prev[i]+'">'+name+'</a>';
						}
						html+='</span>';
					}

					if (data.next && data.next.length > 0)
					{
						html += '&nbsp;<span class="next-buttons">'+lang('next');
						for(var i=0;i<data.next.length; i++)
						{
							var name = lang('next-'+data.next[i]);
							html += ' <a href="javascript:;" shortcut="next,'+data.next[i]+'">'+name+'</a>';
						}
						html+='</span>';
					}
				}

				if (opt.customShortcuts)
				{
					for(var i=0;i<opt.customShortcuts.length; i++)
					{
						var sh = opt.customShortcuts[i];
						html+= '&nbsp;<span class="custom-shortcut"><a href="javascript:;" shortcut="custom">'+sh.name+'</a></span>';
					}
				}
				html += '</div>';
			}

			// Add Custom Values Dom
			if (opt.showCustomValues)
			{
				html += '<div class="customValues"><b>'+(opt.customValueLabel || lang('custom-values'))+'</b>';

				if (opt.customValues)
				{
					for(var i=0;i<opt.customValues.length;i++)
					{
						var val = opt.customValues[i];
							html+= '&nbsp;<span class="custom-value"><a href="javascript:;" custom="'+ val.value+'">'+val.name+'</a></span>';
					}
				}
			}

			html += '</div></div>';


			return $(html);
		}

		function getApplyBtnClass()
		{
			var klass = '';
			if (opt.autoClose === true) {
				klass += ' hide';
			}
			if (opt.applyBtnClass !== '') {
				klass += ' ' + opt.applyBtnClass;
			}
			return klass;
		}

		function getWeekHead()
		{
			var prepend = opt.showWeekNumbers ? '<th>'+lang('week-number')+'</th>' : '';
			if (opt.startOfWeek == 'monday')
			{
				return prepend+'<th>'+lang('week-1')+'</th>' +
					'<th>'+lang('week-2')+'</th>' +
					'<th>'+lang('week-3')+'</th>' +
					'<th>'+lang('week-4')+'</th>' +
					'<th>'+lang('week-5')+'</th>' +
					'<th>'+lang('week-6')+'</th>' +
					'<th>'+lang('week-7')+'</th>';
			}
			else
			{
				return prepend+'<th>'+lang('week-7')+'</th>' +
					'<th>'+lang('week-1')+'</th>' +
					'<th>'+lang('week-2')+'</th>' +
					'<th>'+lang('week-3')+'</th>' +
					'<th>'+lang('week-4')+'</th>' +
					'<th>'+lang('week-5')+'</th>' +
					'<th>'+lang('week-6')+'</th>';
			}
		}

		function isMonthOutOfBounds(month)
		{
			month = moment(month);
			if (opt.startDate && month.endOf('month').isBefore(opt.startDate))
			{
				return true;
			}
			if (opt.endDate && month.startOf('month').isAfter(opt.endDate))
			{
				return true;
			}
			return false;
		}

		function getGapHTML()
		{
			var html = ['<div class="gap-top-mask"></div><div class="gap-bottom-mask"></div><div class="gap-lines">'];
			for(var i=0;i<20;i++)
			{
				html.push('<div class="gap-line">' +
					'<div class="gap-1"></div>' +
					'<div class="gap-2"></div>' +
					'<div class="gap-3"></div>' +
				'</div>');
			}
			html.push('</div>');
			return html.join('');
		}

		function hasMonth2()
		{
			return (!opt.singleMonth);
		}

		function attributesCallbacks(initialObject,callbacksArray,today)
		{
			var resultObject = jQuery.extend(true, {}, initialObject);

			jQuery.each(callbacksArray, function(cbAttrIndex, cbAttr){
				var addAttributes = cbAttr(today);
				for(var attr in addAttributes){
					if(resultObject.hasOwnProperty(attr)){
						resultObject[attr] += addAttributes[attr];
					}else{
						resultObject[attr] = addAttributes[attr];
					}
				}
			});

			var attrString = '';

			for(var attr in resultObject){
				if(resultObject.hasOwnProperty(attr)){
					attrString += attr + '="' + resultObject[attr] + '" ';
				}
			}

			return attrString;
		}

		function daysFrom1970(t)
		{
			return Math.floor(toLocalTimestamp(t) / 86400000);
		}

		function toLocalTimestamp(t)
		{
			if (moment.isMoment(t)) t = t.toDate().getTime();
			if (typeof t == 'object' && t.getTime) t = t.getTime();
			if (typeof t == 'string' && !t.match(/\d{13}/)) t = moment(t,opt.format).toDate().getTime();
			t = parseInt(t, 10) - new Date().getTimezoneOffset()*60*1000;
			return t;
		}

		function createMonthHTML(d)
		{
			var days = [];
			d.setDate(1);
			var lastMonth = new Date(d.getTime() - 86400000);
			var now = new Date();

			var dayOfWeek = d.getDay();
			if((dayOfWeek === 0) && (opt.startOfWeek === 'monday')) {
				// add one week
				dayOfWeek = 7;
			}

			if (dayOfWeek > 0)
			{
				for (var i = dayOfWeek; i > 0; i--)
				{
					var day = new Date(d.getTime() - 86400000*i);
					var valid = isValidTime(day.getTime());
					if (opt.startDate && compare_day(day,opt.startDate) < 0) valid = false;
					if (opt.endDate && compare_day(day,opt.endDate) > 0) valid = false;
					days.push(
					{
						date: day,
						type:'lastMonth',
						day: day.getDate(),
						time:day.getTime(),
						valid:valid
					});
				}
			}
			var toMonth = d.getMonth();
			for(var i=0; i<40; i++)
			{
				var today = moment(d).add(i, 'days').toDate();
				var valid = isValidTime(today.getTime());
				if (opt.startDate && compare_day(today,opt.startDate) < 0) valid = false;
				if (opt.endDate && compare_day(today,opt.endDate) > 0) valid = false;
				days.push(
				{
					date: today,
					type: today.getMonth() == toMonth ? 'toMonth' : 'nextMonth',
					day: today.getDate(),
					time:today.getTime(),
					valid:valid
				});
			}
			var html = [];
			for(var week=0; week<6; week++)
			{
				if (days[week*7].type == 'nextMonth') break;
				html.push('<tr>');
				for(var day = 0; day<7; day++)
				{
					var _day = (opt.startOfWeek == 'monday') ? day+1 : day;
					var today = days[week*7+_day];
					var highlightToday = moment(today.time).format('L') == moment(now).format('L');
					today.extraClass = '';
					today.tooltip = '';
					if(today.valid && opt.beforeShowDay && typeof opt.beforeShowDay == 'function')
					{
						var _r = opt.beforeShowDay(moment(today.time).toDate());
						today.valid = _r[0];
						today.extraClass = _r[1] || '';
						today.tooltip = _r[2] || '';
						if (today.tooltip !== '') today.extraClass += ' has-tooltip ';
					}

					var todayDivAttr = {
						time: today.time,
						'data-tooltip': today.tooltip,
						'class': 'day '+today.type+' '+today.extraClass+' '+(today.valid ? 'valid' : 'invalid')+' '+(highlightToday?'real-today':'')
					};

					if (day === 0 && opt.showWeekNumbers)
					{
						html.push('<td><div class="week-number" data-start-time="'+today.time+'">'+opt.getWeekNumber(today.date)+'</div></td>');
					}

					html.push('<td ' + attributesCallbacks({},opt.dayTdAttrs,today) + '><div ' + attributesCallbacks(todayDivAttr,opt.dayDivAttrs,today) + '>'+showDayHTML(today.time, today.day)+'</div></td>');
				}
				html.push('</tr>');
			}
			return html.join('');
		}

		function showDayHTML(time, date)
		{
			if (opt.showDateFilter && typeof opt.showDateFilter == 'function') return opt.showDateFilter(time, date);
			return date;
		}

		function getLanguages()
		{
			if (opt.language == 'auto')
			{
				var language = navigator.language ? navigator.language : navigator.browserLanguage;
				if (!language) return $.dateRangePickerLanguages['default'];
				language = language.toLowerCase();
				for(var key in $.dateRangePickerLanguages)
				{
					if (language.indexOf(key) !== -1)
					{
						return $.dateRangePickerLanguages[key];
					}
				}
				return $.dateRangePickerLanguages['default'];
			}
			else if ( opt.language && opt.language in $.dateRangePickerLanguages)
			{
				return $.dateRangePickerLanguages[opt.language];
			}
			else
			{
				return $.dateRangePickerLanguages['default'];
			}
		}

		/**
		 * translate language string
		 */
		function lang(t)
		{
			var _t = t.toLowerCase();
			var re = (t in langs) ? langs[t] : ( _t in langs) ? langs[_t] : null;
			var defaultLanguage = $.dateRangePickerLanguages['default'];
			if (re == null) re = (t in defaultLanguage) ? defaultLanguage[t] : ( _t in defaultLanguage) ? defaultLanguage[_t] : '';
			return re;
		}

		function getDefaultTime()
		{
			var defaultTime = opt.defaultTime ? opt.defaultTime : new Date();

			if (opt.lookBehind)
			{
				if (opt.startDate && compare_month(defaultTime, opt.startDate) < 0 ) defaultTime = nextMonth(moment(opt.startDate).toDate());
				if (opt.endDate && compare_month(defaultTime,opt.endDate) > 0 ) defaultTime = moment(opt.endDate).toDate();
			}
			else
			{
				if (opt.startDate && compare_month(defaultTime,opt.startDate) < 0 ) defaultTime = moment(opt.startDate).toDate();
				if (opt.endDate && compare_month(nextMonth(defaultTime),opt.endDate) > 0 ) defaultTime = prevMonth(moment(opt.endDate).toDate());
			}

			if (opt.singleDate)
			{
				if (opt.startDate && compare_month(defaultTime,opt.startDate) < 0 ) defaultTime = moment(opt.startDate).toDate();
				if (opt.endDate && compare_month(defaultTime,opt.endDate) > 0 ) defaultTime = moment(opt.endDate).toDate();
			}

			return defaultTime;
		}

		function resetMonthsView(time)
		{
			if (!time) {
				time = getDefaultTime();
			}

			if (opt.lookBehind)
			{
				showMonth(prevMonth(time),'month1');
				showMonth(time,'month2');
			}
			else
			{
				showMonth(time,'month1');
				showMonth(nextMonth(time),'month2');
			}

			if (opt.singleDate)
			{
				showMonth(time,'month1');
			}

			showSelectedDays();
			showGap();
		}

	};
}));
