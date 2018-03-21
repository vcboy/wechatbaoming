/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-01-13 10:11:11
 * @version $Id$
 */
$(document).ready(function(){
	$("#reback").click(function(){
		history.back();
		console.log('reback');
	});

	window.onpopstate = function(e){
        if(e.state){
            //loaddiv.load(e.state.url);
            console.log('popstate');
        }
    }

    $("#back").click(function(){
    	console.log('back');
    	window.history.back();
    })
})

jQuery.extend({
	selectknow:function(){
		$("#knowbody span").click(function(){
			var i = $(this).attr('i');
			var m = $(this).attr('m');
			var name = $(this).html();
			if(m == 'unselected'){
				$.addfieldset(i,name);
				$(this).attr('m','selected');
			}else{
				$.delfieldset(i);
				$(this).attr('m','unselected');
			}
			//改变样式
			$.antypecss(i,0); 
			//显示模块方案
			
		});
	},
	
	/**
	 * 添加样式
	 */
	antypecss:function(i,n){
		//alert('aaa');
		var css="";
		if(n==0)
			css="a";
		else if(n==1)
			css="t";
		else if(n==2)
			css="f";
		//$("#antype"+i).removeClass();
		$("#spanknow"+i).toggleClass(css);
	},
	/**
	 * 删除样式
	 */
	deltypecss:function(i,n){
		//$("#antype"+i).removeClass();
		var css="";
		if(n==0)
			css="a";
		else if(n==1)
			css="t";
		else if(n==2)
			css="f";
		$("#antype"+i).removeClass(css);
	},
	//复制方案内容
	addfieldset:function(i,name){
		//$('#templatefd').clone(true).appendTo('#tdfieldset').removeClass('templatefd');
		//$('#templatefd').clone(true).appendTo('#tdfieldset').removeClass('templatefd');
		var newfieldset = $('#templatediv').clone(true);
		var newfieldsetstr = $(newfieldset).html()
		////console.log($(newfieldset).html());
		//newfieldset = '{id}aaa';
		var str = newfieldsetstr.replace(/{id}/g,i);
		str = str.replace(/{name}/,name);
		//$("legend").html(i);
		////console.log($("legend").html());
		$(str).appendTo('#tdfieldset');
		//$(str).appendTo('#tdfieldset').removeClass('templatefd');
	},
	//删除方案内容
	delfieldset:function(i){
		//$('#templatefd').clone(true).appendTo('#tdfieldset').removeClass('templatefd');
		//$(obj).parent("td").parent("tr").remove();
		$("#templatefd"+i).remove();
	},
	
	
	antype:function(){

		/*
		$("#exambody input:radio").click(function(){
			var name = $(this).attr('name');
			var i = name.substring(8);
			var val=$(this).parent().parent().find("input:checked").val();
			if(val)
				$.antypecss(i,0);
		});
		*/
		$("#exambody input:radio").click(function(){$.onantypecss(1,this);});		//单选+判断	
		$("#exambody input:checkbox").click(function(){$.onantypecss(2,this);});    //多选
		//$("#exambody input:text").change(function(){$.onantypecss(3,this);});
		$("#exambody textarea").change(function(){$.onantypecss(4,this);});			//填空+简答
		//$("#exambody input:file").change(function(){$.onantypecss(5,this);});
		//$("#exambody input:button").click(function(){$.onantypecss(5,this);});
		//$("span[name*='span']").click(function(){alert(this.attr('name'));});

	},
	
	onantypecss:function(n,obj){
		var name = $(obj).attr('name');
		var i = name.substring(8);	
		if(n==1){
			var val=$(obj).parent().parent().find("input:checked").val();
			
			$.answer(i,val);
			//$.jstorageSet(i,val,1);
			//浏览器是否支持html5
			//if(window.localStorage) $.storage(i,val);
		}
		
		if(n==2){
			var val=$(obj).parent().parent().find("input:checked");
			var arrval = new Array();
			var m = 0;
			$("input[name='"+name+"']:checked").each(function(){		
				arrval[m] = $(this).val();
				m++;
			});
			////console.log(name);
			//var strval = arrval.join(',');
			//var strval = arrval.toString();
			var strval = arrval.join('==*,*==');
			$.answer(i,strval);
			//$.jstorageSet(i,arrval,1);
			
		}
		if(n==3){
			var val=$(obj).parent().parent().find("input:text");
			$.answer(i,val);
			//$.jstorageSet(i,val,1);
			
		}
		if(n==4){
			var val=$(obj).parent().parent().find("textarea").val();
			$.answer(i,val);
			//$.jstorageSet(i,val,1);
			
		}
		//操作题以swfopload方式操作，不需要在此处再处理
		/*
		if(n==5){
			//var val=$(obj).parent().parent().find("input:file");
			var val = $("input[name='question"+i+"']").val();
			//var val = $("#question3441").val();
			//alert(val);
			if(val!=''){
				//$.fileupload(i);
				$.antypecss(i,0);
			}
		}
		*/
	},
	
	removeknow:function(obj,val){
		
		var examtype = $("select[name^='examtype']").eq(1).val();
		var oldcourse_id = $("#oldcourse_id").val();
		
		if(oldcourse_id == ''){
			oldcourse_id = val;
			$("#oldcourse_id").val(val);
			//alert(oldcourse_id);
		}
		
		if(typeof(examtype) != 'undefined'){
			if(confirm("更改科目将清除下面的设置，确定要更改吗？")){
				$("fieldset[id^='templatefd']:gt(0)").remove();
				//$("span[m='selected']").attr('m','unselected');
				$("span[id^='spanknow']").attr('m','unselected');
				$("span[id^='spanknow']").removeClass('a');
				$("#oldcourse_id").val(val);
				$("#tsptotalnum").html(0);
				$("#tsptotalscore").html(0);
			}else{
				////console.log(course_id);
				//$("select[name='course_id']").val(course_id);
				//$(obj).get(0).selectedIndex=0;
				//$(obj).attr('value',val);
				//$(obj).get(0).value = val;
				$(obj).val(oldcourse_id);
				//alert(val);
			}
		}
		
	}
	
})
