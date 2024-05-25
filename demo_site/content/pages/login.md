title:Login Page
---
<button id="modalBtn" onclick="openModal()">Open Modal</button>
<dialog id="dialogModal">
  <article>
    <h2>Confirm Your Membership</h2>
    <p>
      Thank you for signing up for a membership!
      Please review the membership details below:
    </p>
    <ul>
      <li>Membership: Individual</li>
      <li>Price: $10</li>
    </ul>
    <footer>
      <button className="secondary" id="dialogCancel" onclick="cancelModal()">
        Cancel
      </button>
      <button id="dialogConfirm" onclick="confirmModal()">Confirm</button>
    </footer>
  </article>
</dialog>
<script>
    function toggleModal(state){
        dialogModal.open = state;
    }
    function cancelModal(){
        console.log('cancceling')
        toggleModal(false);
    }
    function confirmModal(){
        console.log('confirming')
        toggleModal(false);
    }
    function openModal(){
        console.log('click')
        toggleModal(true);
    };    
</script>