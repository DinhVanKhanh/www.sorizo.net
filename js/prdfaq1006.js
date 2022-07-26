function showIt(src) {
  var child, parent, childNum = 0;
  if ("DIV" == src.tagName) {
    parent = src.parentNode;
    for (var i=0; i < parent.childNodes.length; i++) {
      if (parent.childNodes[i].nodeType != 1) { continue; }
      if (childNum != 1) { childNum++; continue; }
      else { child = parent.childNodes[i]; }
    }
    if (child != null && "LI" == parent.tagName && "UL" == child.tagName) {
      parent.className = ("close" == parent.className ? "open" : "close");
      child.className = ('expanded' == child.className ? 'none' : 'expanded');
    }
  }
}

function categoryOver(src) {
  if ("DIV" == src.tagName) {
    src.style.color = "#4C006F";
    src.style.cursor = (document.all) ? "hand" : "pointer";
  }
}

function out(src) {
  if ("DIV" == src.tagName) {
    src.style.color = "#6C010D";
    src.style.cursor = "auto";
  }
}
