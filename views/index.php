<h1>Hello, <?=$this->e($token)?></h1>

<script src="https://code.jquery.com/jquery.min.js"></script>
<script>
$.ajax({
    // url: 'https://api.github.com/user',
    url: '/pipelines',
    method: 'GET',
    headers: { 'Authorization': 'Bearer <?=$this->e($token)?>' },
    success: function (res) {
        console.log(res);
    }
});
</script>
