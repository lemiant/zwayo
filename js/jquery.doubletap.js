/**
 * Double-tap jQuery Extension 
 * @copy Appcropolis LLC (c) 2012. All rights reserved.
 * @author Raul Sanchez (support@appcropolis.com)
 * @date 2012-10-11
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
 * BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN 
 * ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN 
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
 * SOFTWARE.
 *
 * License and legal information: 
 *
 * http://appcropolis.com/license/
 * http://appcropolis.com/legal/
 * http://appcropolis.com/privacy/
 */
 
(function($){
	// Determine if we on iPhone or iPad
	var isiOS = false;
	var agent = navigator.userAgent.toLowerCase();
	if(agent.indexOf('iphone') >= 0 || agent.indexOf('ipad') >= 0){
		   isiOS = true;
	}
 
	$.fn.doubletap = function(onDoubleTapCallback, onTapCallback, delay){
		var eventName, action;
		delay = delay == null? 500 : delay;
		eventName = isiOS == true? 'touchend' : 'click';
 
		$(this).bind(eventName, function(event){
			  var now = new Date().getTime();
			  var lastTouch = $(this).data('lastTouch') || now + 1 /** the first time this will make delta a negative number */;
			  var delta = now - lastTouch;
			  clearTimeout(action);
			  if(delta<500 && delta>0){
				if(onDoubleTapCallback != null && typeof onDoubleTapCallback == 'function'){
					onDoubleTapCallback(event);
				}
			  }else{
				$(this).data('lastTouch', now);
				action = setTimeout(function(evt){
					if(onTapCallback != null && typeof onTapCallback == 'function'){
						onTapCallback(evt);
					}
					clearTimeout(action);   // clear the timeout
				}, delay, [event]);
			  }
			  $(this).data('lastTouch', now);
		});
	};
})(jQuery);