<script src="<?=base_url()?>vendors/jquery/dist/jquery.min.js"></script>

<script>
    $(function() {
        const proxyurl = "https://cors-anywhere.herokuapp.com/";
        url = "http://kateglo.com/api.php?format=json&phrase=palu";
        var hasil = ''; 
        fetch(proxyurl+url).then(response => hasil = response.json()).then(contents=>console.log(contents)).catch(()=> console.log("Can’t access " + url + " response. Blocked by browser?"));
        var b = '';
        hasil.then(function(result){
            b = result;
        })
        console.log(b);
    });
</script>