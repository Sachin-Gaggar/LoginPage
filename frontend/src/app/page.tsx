import Image from "next/image";
import Link from "next/link";

export default function Home() {
  return (
    <main className="flex min-h-screen flex-col justify-center p-24">
      <div className="text-center">
        <h1 className="text-4xl font-bold mb-4">Welcome to the Home Page</h1>
        <p className="text-lg">This is your main page.</p>
      </div>
      <div className="flex flex-row justify-center pt-10 space-x-4">
        <Link
          href="/register"
          className="px-6 py-3 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition duration-300"
        >
          Go to Registration Page
        </Link>
        <Link
          href="/login"
          className="px-6 py-3 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition duration-300"
        >
          Go to Login Page
        </Link>
      </div>
    </main>
  );
}
