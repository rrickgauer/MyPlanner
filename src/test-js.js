const md = window.markdownit();

function renderMarkdown() {

// var result = md.render('# markdown-it rulezz!');
  
  var input = $("#input").val();

  var result = md.render(input);

  console.log(result);







}