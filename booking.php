<div class="container-fluid">
	<form action="" id="manage-book">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
		<input type="hidden" name="venue_id" value="<?php echo isset($_GET['venue_id']) ? $_GET['venue_id'] :'' ?>">
		<div class="form-group">
			<label for="" class="control-label">Full Name</label>
			<input type="text" name="name" value="<?php echo isset($name) ? $name :'' ?>" required class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Address</label>
			<textarea cols="30" rows="2" required name="address" class="form-control"><?php echo isset($address) ? $address :'' ?></textarea>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Email</label>
			<input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email :'' ?>" required>
			<small id="email-error" class="text-danger"></small> <!-- Error message for email validation -->
		</div>
		<div class="form-group">
			<label for="" class="control-label">Contact *</label>
			<input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? $contact :'' ?>" required>
			<small id="contact-error" class="text-danger"></small> <!-- Error message for contact validation -->
		</div>
		<div class="form-group">
			<label for="" class="control-label">Duration</label>
			<input type="text" class="form-control" name="duration" value="<?php echo isset($duration) ? $duration :'' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Desired Event Schedule</label>
			<input type="text" class="form-control datetimepicker" name="schedule" value="<?php echo isset($schedule) ? $schedule :'' ?>" required>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button> <!-- Added a submit button -->
	</form>
</div>
<script>
	$('.datetimepicker').datetimepicker({
		format:'Y/m/d H:i',
		startDate: '+3d'
	})
	$('#manage-book').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')

		// Perform email validation
		var email = $('input[name="email"]').val();
		if (!validateEmail(email)) {
			$('#email-error').text('Please enter a valid email.');
			end_load();
			return;
		}
		$('#email-error').text('');

		// Perform contact validation
		var contact = $('input[name="contact"]').val();
		if (!validateContact(contact)) {
			$('#contact-error').text('Please enter a valid contact number.');
			end_load();
			return;
		}
		$('#contact-error').text('');

		// Proceed with form submission
		$.ajax({
			url: 'admin/ajax.php?action=save_book',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success:
			function(resp){
				if(resp == 1){
					alert_toast("Book Request Sent.", 'success');
					end_load();
					uni_modal("", "book_msg.php");
				}
			}
		});
	});

	// Email validation function
	function validateEmail(email) {
		var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return pattern.test(email);
	}

	// Contact validation function
	function validateContact(contact) {
		var pattern = /^\d{1,10}$/;
		return pattern.test(contact);
	}
</script>
