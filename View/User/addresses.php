<?php
require_once __DIR__ . '/../../config/dbConnectionSingelton.php';
require_once __DIR__ . '/../../Model/Address/Address.php';

$db = Database::getInstance()->getConnection();
$addressModel = new Address($db);

// Get user's addresses
$userAddresses = $addressModel->getUserAddresses($_SESSION['user_id']);

// Get countries for the form
$countries = $addressModel->getCountries();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Addresses</title>
    <link rel="stylesheet" href="/swe_master/assets/css/base.css">
    <link rel="stylesheet" href="/swe_master/assets/css/addresses.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <h1>My Addresses</h1>

        <!-- Add New Address Button -->
        <button class="add-address-btn" onclick="showAddressForm()">Add New Address</button>

        <!-- Address List -->
        <div class="addresses-grid">
            <?php foreach ($userAddresses as $address): ?>
                <div class="address-card">
                    <div class="address-header">
                        <h3><?php echo htmlspecialchars($address['location_name']); ?></h3>
                        <?php if ($address['is_default']): ?>
                            <span class="default-badge">Default</span>
                        <?php endif; ?>
                    </div>
                    <div class="address-details">
                        <p><?php echo htmlspecialchars($address['address_line1']); ?></p>
                        <?php if (!empty($address['address_line2'])): ?>
                            <p><?php echo htmlspecialchars($address['address_line2']); ?></p>
                        <?php endif; ?>
                        <p class="location-type"><?php echo ucfirst($address['location_type']); ?></p>
                    </div>
                    <div class="address-actions">
                        <button onclick="editAddress(<?php echo $address['id']; ?>)" class="edit-btn">Edit</button>
                        <?php if (!$address['is_default']): ?>
                            <button onclick="setDefaultAddress(<?php echo $address['id']; ?>)" class="default-btn">Set as Default</button>
                        <?php endif; ?>
                        <button onclick="deleteAddress(<?php echo $address['id']; ?>)" class="delete-btn">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Add/Edit Address Form -->
        <div id="addressForm" class="address-form" style="display: none;">
            <h2>Add New Address</h2>
            <form id="addressFormElement" action="index.php?controller=User&action=addAddress" method="POST">
                <div class="form-group">
                    <label for="country">Country:</label>
                    <select id="country" name="country" required onchange="loadStates()">
                        <option value="">Select Country</option>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?php echo $country['id']; ?>"><?php echo htmlspecialchars($country['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="state">State/Province:</label>
                    <select id="state" name="state" required onchange="loadCities()">
                        <option value="">Select State</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <select id="city" name="city" required>
                        <option value="">Select City</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="addressLine1">Address Line 1:</label>
                    <input type="text" id="addressLine1" name="addressLine1" required>
                </div>

                <div class="form-group">
                    <label for="addressLine2">Address Line 2 (Optional):</label>
                    <input type="text" id="addressLine2" name="addressLine2">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="isDefault" value="1">
                        Set as default address
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">Save Address</button>
                    <button type="button" class="cancel-btn" onclick="hideAddressForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddressForm() {
            document.getElementById('addressForm').style.display = 'block';
        }

        function hideAddressForm() {
            document.getElementById('addressForm').style.display = 'none';
        }

        function loadStates() {
            const countryId = document.getElementById('country').value;
            if (!countryId) return;

            fetch(`index.php?controller=User&action=getStates&countryId=${countryId}`)
                .then(response => response.json())
                .then(states => {
                    const stateSelect = document.getElementById('state');
                    stateSelect.innerHTML = '<option value="">Select State</option>';
                    states.forEach(state => {
                        stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                    });
                    document.getElementById('city').innerHTML = '<option value="">Select City</option>';
                });
        }

        function loadCities() {
            const stateId = document.getElementById('state').value;
            if (!stateId) return;

            fetch(`index.php?controller=User&action=getCities&stateId=${stateId}`)
                .then(response => response.json())
                .then(cities => {
                    const citySelect = document.getElementById('city');
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    cities.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                });
        }

        function editAddress(addressId) {
            // TODO: Implement edit functionality
            alert('Edit functionality coming soon!');
        }

        function setDefaultAddress(addressId) {
            if (confirm('Set this as your default address?')) {
                fetch(`index.php?controller=User&action=setDefaultAddress&addressId=${addressId}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Failed to set default address');
                    }
                });
            }
        }

        function deleteAddress(addressId) {
            if (confirm('Are you sure you want to delete this address?')) {
                fetch(`index.php?controller=User&action=deleteAddress&addressId=${addressId}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete address');
                    }
                });
            }
        }
    </script>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html> 