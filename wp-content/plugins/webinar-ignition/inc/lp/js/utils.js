
// fnc :: wait
// --------------------------------------------------------------------------------------
   window.wait = function(dfn)
   {
      var tmo = 50;
      var rsl = null;
      var itv = setInterval
      (
         function()
         {
            rsl = (function(on)
            {
               if (typeof on == 'string')
               { return document.getElementById(on); }

               if (typeof on == 'function')
               { return on(); }

               return on;
            }(dfn.on));

            if (rsl)
            {
               clearInterval(itv);

               if (rsl)
               {
                  dfn.do();
                  return true;
               }
            }
         },

         tmo
      );
   };
// --------------------------------------------------------------------------------------



// api :: modal
// --------------------------------------------------------------------------------------
   window.modal = function(dfn)
   {
      modal.exit();

      var mdl = document.createElement('div');
      var htm = '';

      mdl.className = 'wi_mdl_ovl';

      htm += '<div class="wi_mdl_box">';
      htm += '<div class="wi_mdl_head">'+dfn.head+'</div>';
      htm += '<div class="wi_mdl_body"></div>';
      htm += '<div class="wi_mdl_foot"></div>';
      htm += '</div>';

      mdl.innerHTML = htm;

      if (typeof dfn.body == 'string')
      { mdl.getElementsByClassName('wi_mdl_body')[0].innerHTML = dfn.body; }
      else
      { mdl.getElementsByClassName('wi_mdl_body')[0].appendChild(dfn.body); }

      mdl.getElementsByClassName('wi_mdl_foot')[0].appendChild
      ((function()
      {
         var rsl = document.createElement('div');
         var btn = null;

         dfn.foot.forEach(function(item, index) {
            btn = document.createElement('button');

            btn.className = 'wi_mdl_btn_auto';
            btn.innerHTML = item.name;
            if( item.callback && typeof item.callback === 'function' ) {
               btn.onclick = item.callback;
            }

            rsl.appendChild(btn);
         });

         return rsl;
      }()));

      document.addEventListener
      (
         "keydown",
         function(event)
         {
            if (event.keyCode == 27)
            { modal.exit(); }
         },
         false
      );

      document.body.appendChild(mdl);

      if (dfn.evnt)
      {
         if (dfn.evnt.init)
         { dfn.evnt.init(dfn); }
      }

   };

   modal.exit = function()
   {
      var lst = [].slice.call(document.getElementsByClassName('wi_mdl_ovl'));

      for (var itm in lst)
      {
         lst[itm].parentNode.removeChild(lst[itm]);
      }
   };
// --------------------------------------------------------------------------------------



// fnc :: getVar - for `ar test` fix
// --------------------------------------------------------------------------------------
   function getVar(nme)
   {
      var qry = window.location.search.substring(1);
      var vrs = qry.split("&");
      var pts = null;

      for (var i=0; i < vrs.length; i++)
      {
         pts = vrs[i].split("=");

         if (pts[0] == nme)
         { return pts[1]; }
      }

      return false;
   }
// --------------------------------------------------------------------------------------



// fnc :: arCheck - if in ar test mode, this halts page redirection and shows results
// --------------------------------------------------------------------------------------
   function arCheck()
   {
         var frm = document.getElementById('ar_submit_iframe');

         if (frm && !frm.done)
         {
            frm.parentNode.removeChild(frm);
            frm.className = 'wi_frame';
            frm.done = true;

            modal
            ({
               name:'ar_test_modal',
               head:'AR Integration Test',
               body:frm,
               foot:
               {
                  done:function(){ modal.exit(); }
               },

               evnt:
               {
                  init:function()
                  {
                     HTMLFormElement.prototype.submit.call(jQuery("#AR-INTEGRATION")[0]);
                  }
               }
            });
         }

         //document.getElementsByClassName('arintegration')[0].style.display = 'block';
   }
// --------------------------------------------------------------------------------------
