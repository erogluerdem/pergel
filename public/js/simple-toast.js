(function(window){
    window.simpleToast = function(message, type){
        type = type || 'info';
        var colors = { info: '#0d6efd', success: '#198754', error: '#dc3545' };
        var bg = colors[type] || colors.info;
        var t = document.createElement('div');
        t.innerText = message;
        t.style.position = 'fixed';
        t.style.right = '20px';
        t.style.top = '20px';
        t.style.background = bg;
        t.style.color = 'white';
        t.style.padding = '8px 12px';
        t.style.borderRadius = '6px';
        t.style.boxShadow = '0 6px 18px rgba(0,0,0,0.18)';
        t.style.zIndex = 12000;
        t.style.fontSize = '14px';
        document.body.appendChild(t);
        setTimeout(function(){ t.style.transition = 'opacity 0.4s'; t.style.opacity = '0'; }, 2200);
        setTimeout(function(){ try{ t.parentNode.removeChild(t); }catch(e){} }, 2600);
    };
})(window);
