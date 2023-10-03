<div class="row mb-4">
  <x-form.select name="title" label="Title *" :selected="old('title')" optionsType="array" :options="USER_TITLE"
    parentClass="col-sm-6 mb-4" required />

  <x-form.input name="last_name" label="Last Name *" type="text" :value="old('last_name')" parentClass="col-sm-6 mb-4"
    pattern="^[a-zA-Z0-9\-]{2,70}$" minlength="2" maxlength="70" placeholder="e.g. Adelakin" required />

  <x-form.input name="first_name" label="First Name *" type="text" :value="old('first_name')" parentClass="col-sm-6 mb-4"
    pattern="^[a-zA-Z\-]{1,70}$" minlength="1" maxlength="70" placeholder="e.g. Bob" required />

  <x-form.input name="middle_name" label="Middle Name" type="text" :value="old('middle_name')" parentClass="col-sm-6 mb-4"
    pattern="^[a-zA-Z\-]{1,70}$" minlength="1" maxlength="70" placeholder="e.g. Adelakin" />
</div>

<div class="row">
  <x-form.select name="faculty_id" label="Faculty *" :selected="old('faculty_id')" optionsType="object" :options="$faculties"
    objKey="id" objValue="faculty" parentClass="col-lg-4 col-sm-6 mb-4" />

  <x-form.input name="email" label="Email *" type="email" :value="old('email')" parentClass="col-lg-4 col-sm-6 mb-4"
    placeholder="e.g. abc@xyz.com" required />

  <x-form.input name="phone" label="Phone *" type="text" :value="old('phone')" parentClass="col-lg-4 col-sm-6 mb-4"
    placeholder="e.g. 08165346948" pattern="^[0][7-8][0-9]{9,9}$" required />

  <x-form.select name="account_type" label="Account Type *" :selected="old('account_type')" optionsType="array" :options="USER_TYPE"
    parentClass="col-sm-6 mb-4" required />

  @if (Request::segment(3) == 'register')
    <x-form.input name="password" label="Password *" type="text" :value="old('password')" parentClass="col-sm-6 mb-4"
      placeholder="e.g. => 8 chars" required />
  @endif
</div>
