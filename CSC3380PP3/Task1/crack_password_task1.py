import hashlib

# Selva's password hash from database
target_hash = "c60266a8adad2f8ee67d793b4fd3fd0ffd73cc61"

# First 100 common passwords from the project document
common_passwords = [
    "password", "123456", "12345678", "1234", "qwerty", "12345",
    "dragon", "baseball", "football", "letmein",
    "monkey", "696969", "abc123", "mustang", "michael",
    "shadow", "master", "jennifer", "111111", "2000",
    "jordan", "superman", "harley", "1234567", "hunter", "fuckme",
    "2001", "123456789", "test", "batman",
    "trustno1", "thomas", "tigger", "robert", "access", "love",
    "buster", "1234567890", "soccer", "hockey",
    "killer", "george", "sexy", "andrew", "charlie", "super",
    "asshole", "fuckyou", "dallas", "jessica",
    "panties", "pepper", "1111", "austin", "william", "daniel",
    "golfer", "summer", "heather", "hammer",
    "yankees", "joshua", "maggie", "biteme", "enter", "ashley",
    "thunder", "cowboy", "silver", "richard",
    "orange", "merlin", "michelle", "corvette", "bigdog", "cheese",
    "matthew", "121212", "patrick", "martin",
    "freedom", "ginger", "blowjob", "nicole", "sparky", "yellow",
    "camaro", "secret", "dick", "falcon",
    "taylor", "11111111", "131313", "123123", "bitch", "hello",
    "scooter", "please", "porsche", "guitar", "chelsea"
]

def crack_password(target_hash, password_list):
    """
    Crack password by comparing SHA-1 hashes
    """
    print("Starting password cracking...")
    print(f"Target hash: {target_hash}\n")
    
    for password in password_list:
        # Calculate SHA-1 hash of the password
        hash_object = hashlib.sha1(password.encode())
        password_hash = hash_object.hexdigest()
        
        # Check if it matches
        if password_hash == target_hash:
            print(f"✓ PASSWORD FOUND: {password}")
            print(f"  Hash verification: {password_hash}")
            return password
    
    print("✗ Password not found in common password list")
    return None

# Run the cracker
if __name__ == "__main__":
    cracked_password = crack_password(target_hash, common_passwords)
    
    if cracked_password:
        print(f"\n{'='*50}")
        print(f"Selva's password is: {cracked_password}")
        print(f"{'='*50}")
        
        # Save to file
        with open('passwordTask1.txt', 'w') as f:
            f.write(cracked_password)
        print("\nPassword saved to passwordTask1.txt")