"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

export default function RegistrationForm() {
  const [mobileNumber, setMobileNumber] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");
  const router = useRouter();
  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await fetch(
        "http://localhost:8888/login/api.php/login",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            mobile_number: mobileNumber,
            password: password,
          }),
        }
      );

      const apiResult = await response.json();

      if (apiResult.success) {
        // Handle successful login here
        setMessage("Login successful!");
        const { first_name, last_name } = apiResult.data;
        localStorage.setItem("access_token", apiResult.access_token);

        router.push(`/home?first_name=${first_name}&last_name=${last_name}`);
      } else {
        setMessage(`Error: ${apiResult.error}`);
      }
    } catch (error) {
      setMessage("An error occurred while logging in. Please try again.");
    }
  };

  return (
    <div className="flex flex-col items-center bg-white p-6 rounded-lg shadow-md w-96 max-w-full mx-auto">
      <form onSubmit={handleSubmit} className="w-full">
        <h2 className="text-2xl font-bold mb-4 text-center">Login</h2>
        <div className="mb-4">
          <label className="block mb-2">Mobile Number</label>
          <input
            type="text"
            value={mobileNumber}
            onChange={(e) => setMobileNumber(e.target.value)}
            className="w-full p-2 border rounded"
          />
        </div>
        <div className="mb-4">
          <label className="block mb-2">Password</label>
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="w-full p-2 border rounded"
          />
        </div>
        <button
          type="submit"
          className="w-full p-2 bg-blue-500 text-white rounded"
        >
          Login
        </button>
      </form>
      <div className="mt-6 w-full">
        <p className="text-center text-sm text-gray-500 mb-2">Or Login with</p>
        <div className="flex flex-col space-y-2">
          <button className="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-md shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
            Login with Google
          </button>
          <button className="w-full py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black">
            Login with Apple
          </button>
          <button className="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
            Login with Facebook
          </button>
        </div>
      </div>
    </div>
  );
}