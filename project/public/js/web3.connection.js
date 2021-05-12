function setWeb3Connection() {
    "use strict";

    let host = document.getElementById('web3-eth-host').value;

    try {

    let web3 = new Web3(host);

        web3.eth.isSyncing().then((state)=>{
            if(!state) {
                document.getElementById("eth-connection-form").submit();
            } else {
                alert("The Node is not fully synced yet.");
            }
        }).catch((error)=>{
            alert("The Node connection was not established. Please make sure the node is up and running and allows remote connection");
        });

    } catch (e) {
        alert("The provided hostname is invalid");
    }
}
