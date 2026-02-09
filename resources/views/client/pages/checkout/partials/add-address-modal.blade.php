<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    <span id="modalAddressTypeLabel">Ajouter une nouvelle adresse</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addAddressForm">
                    @csrf
                    <div class="row g-3">
                        <!-- First Name -->
                        <div class="col-md-6">
                            <label for="addFirstName" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="addFirstName" name="first_name" required>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6">
                            <label for="addLastName" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="addLastName" name="last_name" required>
                        </div>

                        <!-- Address Line 1 -->
                        <div class="col-12">
                            <label for="addLineOne" class="form-label">Adresse ligne 1 *</label>
                            <input type="text" class="form-control" id="addLineOne" name="line_one" required>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="col-12">
                            <label for="addLineTwo" class="form-label">Adresse ligne 2</label>
                            <input type="text" class="form-control" id="addLineTwo" name="line_two">
                        </div>

                        <!-- City -->
                        <div class="col-md-6">
                            <label for="addCity" class="form-label">Ville *</label>
                            <input type="text" class="form-control" id="addCity" name="city" required>
                        </div>

                        <!-- Postcode -->
                        <div class="col-md-6">
                            <label for="addPostcode" class="form-label">Code postal *</label>
                            <input type="text" class="form-control" id="addPostcode" name="postcode" required>
                        </div>

                        <!-- Country -->
                        <div class="col-md-6">
                            <label for="addCountry" class="form-label">Pays *</label>
                            <select class="form-select" id="addCountry" name="country_id" required>
                                <option value="">Sélectionner un pays</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $country->emoji ?? '' }} {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- State -->
                        <div class="col-md-6">
                            <label for="addState" class="form-label">État / Province</label>
                            <input type="text" class="form-control" id="addState" name="state_id">
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="addPhone" class="form-label">Téléphone *</label>
                            <input type="tel" class="form-control" id="addPhone" name="contact_phone" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="addEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="addEmail" name="contact_email">
                        </div>

                        <!-- Company Name -->
                        <div class="col-12">
                            <label for="addCompany" class="form-label">Nom de l'entreprise</label>
                            <input type="text" class="form-control" id="addCompany" name="company_name">
                        </div>

                        <!-- Default Address -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="addDefaultAddress" name="shipping_default" value="1">
                                <label class="form-check-label" for="addDefaultAddress">
                                    Définir comme adresse par défaut
                                </label>
                            </div>
                        </div>

                        <!-- Hidden field for type -->
                        <input type="hidden" name="type" id="addressTypeField" value="shipping">
                    </div>

                    <div class="mt-4">
                        <div id="addAddressMessage"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveAddressBtn">
                    <i class="bi bi-save me-1"></i>
                    Enregistrer l'adresse
                </button>
            </div>
        </div>
    </div>
</div>
