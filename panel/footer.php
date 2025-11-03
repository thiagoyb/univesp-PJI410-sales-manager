	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/f4bd964e32.js" crossorigin="anonymous"></script>
	<script type="application/javascript" src="<?= URL_HOME; ?>assets/js/Utils.js"></script>
	<script type="application/javascript" src="<?= URL_HOME; ?>assets/js/Ajax.js"></script>

	<script type="application/javascript" src="<?= URL_HOME; ?>assets/js/popper.min.js"></script>
	<script type="application/javascript" src="<?= URL_HOME; ?>assets/js/bootstrap.min.js"></script>
<SCRIPT>
	class Swal{
		static alertMsg(title, msg, type){
			return swal(title, msg, {
				icon : type,
				buttons: {
					confirm: {
					  className :  type=='error' ? 'btn btn-danger' : 'btn btn-success'
					}
				},
			});
		}
		static alertError(title, msg){
			return swal(title, msg, {
				icon : 'error',
				buttons: {
					confirm: {
					  className :  'btn btn-danger'
					}
				},
			});
		}
		static alertSuccess(title, msg){
			return swal(title, msg, {
				icon : 'success',
				buttons: {
					confirm: {
					  className :  'btn btn-success'
					}
				},
			});
		}
	}
</SCRIPT>