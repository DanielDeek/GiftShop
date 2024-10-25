@extends('home.index')

@section('content')
    <section class="contact_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact_info">
                        <h2>Contact Us</h2>
                        <ul class="info_list">
                            <li>
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span> Gb road 123 London, UK </span>
                            </li>
                            <li>
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>+01 12345678901</span>
                            </li>
                            <li>
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span> demo@gmail.com</span>
                            </li>
                        </ul>
                    </div>
                    <div class="additional_info mt-4">
                        <h3>Business Hours</h3>
                        <ul>
                            <li>Monday - Friday: 9:00 AM - 6:00 PM</li>
                            <li>Saturday: 10:00 AM - 4:00 PM</li>
                            <li>Sunday: Closed</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact_form">
                        <form action="/contact" method="POST">
                            @csrf
                            <div class="form_group">
                                <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form_group">
                                <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form_group">
                                <input type="phone" name="phone" placeholder="Your Phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form_group">
                                <textarea name="message" class="message-box" placeholder="Your Message" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form_group text-right">
                                <button type="submit">SEND MESSAGE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        
        .contact_section {
            padding: 60px 0;
            background-color: #f9f9f9;
        }

        .contact_info, .additional_info {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            height: 293px;
        }

        .contact_info h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .info_list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .info_list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            color: #666;
        }

        .info_list li i {
            font-size: 20px;
            margin-right: 10px;
        }

        .additional_info h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .additional_info ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .additional_info ul li {
            margin-bottom: 10px;
            color: #666;
        }

        .contact_form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            position: relative; /* Add position relative for absolute positioning */
        }

        .form_group {
            margin-bottom: 20px;
        }

        .form_group input[type="text"],
        .form_group input[type="email"],
        .form_group input[type="phone"],
        .form_group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form_group textarea {
            height: 150px;
        }

        .form_group button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            position: absolute;
            bottom: 30px;
            right: 30px;
        }

        .form_group button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
@endsection
